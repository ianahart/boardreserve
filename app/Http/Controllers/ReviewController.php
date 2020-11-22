<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Review;
use App\Models\Snowboard;

class ReviewController extends Controller
{
    public function create(Request $request)
    {
        return view('reviews.showform', [

            'model' => $request->model,
            'snowboard' => $request->boardId
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'content' => [
                'required',
                'min:10',
                'max:300'
            ]
        ]);

        if ($this->checkForExistingReview($user, $request->boardId)) {

            return back()->with([
                'duplicateError' => 'Sorry you have already reviewed this board'
            ]);
        } else {

            $review = new Review;

            $review->content = $request->content;
            $review->rating = $request->rating;
            $review->boardId = $request->boardId;
            $review->username = $user->username;
            $review->userId = $user->id;
            $review->edited = false;
            $review->readable_date = $this->formatReviewTime(date('Y-m-d H:i:s', time()));

            $review->save();

            return redirect('/snowboards/' . $request->boardId,)
                ->with('review_success', 'Your review was added');
        }
    }

    private function checkForExistingReview($user, $boardId)
    {
        $foundReview = Review::where([

            ['userId', '=', $user->id],
            ['boardId', '=', $boardId]

        ])->first();

        if (!empty($foundReview)) {

            return true;
        } else {

            return false;
        }
    }

    public function show(Request $request, $id)
    {
        $commentOwner = Auth::user()->id;

        $reviews = Review::where('boardId', '=', $id)
            ->latest()
            ->get();


        $totalReviews = count($reviews);

        $avgReviewRating = $this->getAverageRating($reviews);

        return view('reviews.showreviews', [

            'totalReviews' => $totalReviews,
            'reviews' => $reviews,
            'model' => $request->board,
            'commentOwner' => $commentOwner,
            'avgReviewRating' => $avgReviewRating
        ]);
    }

    private function getAverageRating($reviews)
    {
        $ratingSum = NULL;

        foreach ($reviews as $review) {

            $ratingSum += array_sum($this->sumRatings($review));
        }

        if ($ratingSum <= 0) {

            return 0;
        } else {

            $avgRating = round($ratingSum / count($reviews), 1);

            return $avgRating;
        }
    }

    private function sumRatings($review)
    {
        $ratingsSum =  array_map(

            function ($val, $key) {

                if ($key === 'rating') {

                    return $val;
                }
            },
            json_decode(json_encode($review), true),

            array_keys(json_decode(json_encode($review), true))
        );
        return $ratingsSum;
    }
    private function formatReviewTime($time)
    {
        $day = date('d', strtotime($time));

        $month = date('m', strtotime($time));

        $year = date('Y', strtotime($time));

        $formattedTime = $month . "-" . $day . "-" . $year;

        return $formattedTime;
    }

    public function destroy($id)
    {
        $comment = Review::where('id', '=', $id)
            ->first();

        $boardId = $comment->boardId;

        $board = Snowboard::where('id', '=', $boardId)
            ->select('id', 'model')
            ->first();

        if (!$comment) {

            return back()->with('error', 'Could not delete comment');
        } else {

            $comment->delete();

            return redirect('/reviews/' . $boardId . '?board=' . $board->model)
                ->with('delete_review_success', 'Your comment was deleted');
        }
    }

    public function showUpdate(Request $request, $id)
    {
        $review = Review::where('id', '=', $id)
            ->select('id', 'content', 'rating')
            ->first();

        if ($review) {

            return view('reviews.showupdateform', [
                'model' => $request->query('model'),
                'review' => $review
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $review = Review::where('id', '=', $id)
            ->select('*')
            ->first();

        $snowboard = Snowboard::where('id', '=', $review->boardId)
            ->select('id', 'model')
            ->first();

        $review->content = $request->input('content');

        $review->rating = $request->input('rating');

        if (!$review->edited) {

            $review->edited = true;
        }

        $review->save();

        return redirect('/reviews/' . $review->boardId . '?board=' . $snowboard->model)
            ->with('update_review_success', 'Your comment was updated');
    }
}
