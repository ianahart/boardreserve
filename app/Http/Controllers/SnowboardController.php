<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Models\Snowboard;
use App\Models\User;
use App\Models\Review;

class SnowboardController extends Controller
{

    public function showSnowboardForm()
    {
        return view('snowboards.create');
    }

    public function index()
    {
        Session::put('selected_value', '');

        $snowboards = Snowboard::select('brand', 'model', 'quantity', 'image', 'id', 'seller', 'price')->get();

        return view('snowboards.index', ['snowboards' => $snowboards]);
    }

    public function createSnowboardForm(Request $request)
    {
        // dd($request->file('file'));
        $validatedData = $request->validate([
            'brand' => [
                'required',
                'regex:/(^[A-Za-z0-9 ]+$)+/',
                'min:2',
                'max:30',

            ],
            'model' => [
                'required',
                'min: 4',
                'max:30',
            ],
            'shape' => [
                'required',
                'min:4',
                'max: 30'
            ],
            'length' => [
                'required',
                'size:3',
                'alpha_num'
            ],
            'price' => [
                'required',
                'lt:1000',
                // 'numeric',
                'regex:/^\d+(\.\d{1,2})?$/'
            ],
            'file' => [
                'max:2000',
                'image'
            ],
            'desc' => [
                'required',
                'max:1000',
                'min:20',
            ],
            'quantity' => [
                'required',
                'numeric',
                'max:100',
                'min:1',
            ]
        ]);

        $snowboard = new Snowboard;

        $file = $request->file('file');

        if (!is_null($file)) {
            $imageFilePath = $this->uploadPhotoToS3($file);

            $snowboard->image = $imageFilePath;
        } else {
            $snowboard->image = '';
        }

        $snowboard->brand = ucwords($request->input('brand'));
        $snowboard->model = ucwords($request->input('model'));
        $snowboard->shape = ucwords($request->input('shape'));
        $snowboard->category = ucwords($request->input('category'));
        $snowboard->seller = Session::get('userID');
        $snowboard->size = $request->input('length');
        $snowboard->price = $request->input('price');
        $snowboard->desc = ucfirst($request->input('desc'));
        $snowboard->quantity = $request->input('quantity');


        $snowboard->save();

        return redirect('/snowboards')
            ->with('success', 'Your snowboard has been added to the shop');
    }

    public function showDetails($id)
    {
        $snowboard = Snowboard::findOrFail($id);

        $sellerUsername = $this->getSellerUsername($id);

        $AVGReviewRating = $this->getAVGReviewRating($id);

        return view('snowboards.show', [
            'snowboard' => $snowboard,
            'sellerUsername' => $sellerUsername,
            'AVGReviewRating' => $AVGReviewRating
        ]);
    }

    private function getAVGReviewRating($boardId)
    {
        $reviews = Review::where('boardId', '=', $boardId)
            ->select('rating')
            ->get();

        $sumOfRatings = 0;

        foreach ($reviews as $review) {

            $sumOfRatings += $review->rating;
        }
        if ($sumOfRatings <= 0) {

            return 0;
        } else {

            return round($sumOfRatings / count($reviews), 1);
        }
    }

    public function createSort(Request $request)
    {
        $order = null;

        $condition = null;

        Session::put('selected_value', $request->input('sort'));

        if (strpos($request->input('sort'), 'alphabetical asc') > -1) {

            $order = 'asc';
        } else if (strpos($request->input('sort'), 'alphabetical desc') > -1) {

            $order = 'desc';
        }

        if (!is_null($order)) {

            $snowboards = Snowboard::select('brand', 'model', 'image', 'id', 'seller', 'price')
                ->orderBy('brand', $order)
                ->get();

            return view('snowboards.index', [
                'snowboards' => $snowboards,
                'selected_value' => $request->input('sort')
            ]);
        }

        if (strpos($request->input('sort'), '> 500') > -1) {

            $condition = $this->makeReqInputAnArray($request->input('sort'));
        } else if (strpos($request->input('sort'), '< 500') > -1) {

            $condition = $this->makeReqInputAnArray($request->input('sort'));
        }

        if (!is_null($condition)) {

            $snowboards = Snowboard::where('price', $condition[0], $condition[1])
                ->select('brand', 'model', 'image', 'id', 'seller', 'price')
                ->get();
            return redirect('/snowboards?selected=' . $request->input('sort'));

            // return view('snowboards.index', [
            //     'snowboards' => $snowboards
            // ]);
        }

        if ($request->input('sort') === 'new') {

            $snowboards = Snowboard::select('brand', 'model', 'image', 'id', 'seller')
                ->orderBy('created_at', 'desc')
                ->take(3)
                ->get();

            return redirect('/snowboards?selected=' . $request->input('sort'));


            // return view('snowboards.index', [
            //     'snowboards' => $snowboards,
            // ]);
        }
    }

    public function indexRedirect()
    {
        return redirect('/snowboards');
    }

    private function makeReqInputAnArray($input)
    {
        return explode(' ', $input);
    }

    private function  uploadPhotoToS3($file)
    {
        $filePath = time() . '_' . $file->getClientOriginalName();

        Storage::disk('s3')->put($filePath, fopen($file, 'r+'), 'public');

        return $filePath;
    }

    private function getSellerUsername($boardID)
    {
        $board = Snowboard::select('seller')
            ->where('id', $boardID)
            ->first();

        $user = User::select('username')
            ->where('id', $board->seller)
            ->first();

        return $user->username;
    }
}
