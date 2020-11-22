<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

use App\Mail\OrderConfirmation;

use App\Models\User;
use App\Models\Snowboard;
use App\Models\Purchase;
use App\Models\Review;


class UserController extends Controller
{
    public function showProfile(Request $request, $userID)
    {
        $user = User::findOrFail($userID);

        $joinedAtDate = $this->formatUserJoinedDate($user);

        if (isset($request->board)) {

            $relatedBoards = $this->getUsersRelatedBoards($request->board, $userID);

            return view('users.show', [
                'user' => $user,
                'relatedBoards' => $relatedBoards,
                'prevPage' => $request->board,
                'joined' => $joinedAtDate
            ]);
        } else {

            return view('users.show', [
                'user' => $user,
                'relatedBoards' => null,
                'prevPage' => $request->board,
                'joined' => $joinedAtDate
            ]);
        }
    }

    public function showCurrentUserProfile()
    {
        $currentUserId = Auth::user()->id;

        $user = User::where('id', '=', $currentUserId)
            ->select('*')
            ->first();

        if (!$user) {

            return redirect('/login')
                ->with('unauthorized', 'Sorry, you are not authorized to view this page')
                ->setStatusCode(401);
        } else {

            $formattedJoinDate = $this->formatUserJoinedDate($user);

            return response()
                ->view('users.showcurrentuser', [

                    'user' => $user,
                    'joinedDate' => $formattedJoinDate
                ])
                ->setStatusCode(200);
        }
    }

    public function showCurrentUserSnowboards()
    {
        $snowboards = Snowboard::where('seller', '=', Auth::user()->id)
            ->select('brand', 'model', 'id', 'image', 'price', 'quantity')
            ->get();

        $fullName = explode(' ', Auth::user()->name);

        $potentialSaleTotal = NULL;

        foreach ($snowboards as $snowboard) {

            $potentialSaleTotal += ($snowboard->quantity * $snowboard->price);
        }

        return view('users.showsnowboards', [

            'snowboards' => $snowboards,
            'name' => $fullName[0],
            'potentialSaleTotal' => number_format($potentialSaleTotal, 2)
        ]);
    }

    public function showCart()
    {
        Session::forget('user_message');

        $authUser = Auth::user()->id;

        $user = User::where('id', '=', $authUser)
            ->select('shopping_cart')
            ->first();

        $userMessage = User::where('id', '=', $authUser)
            ->select('id', 'shopping_cart_message')
            ->first();
        if (strlen($userMessage->shopping_cart_message) > 0) {

            Session::put('user_message', $userMessage->shopping_cart_message);

            $userMessage->shopping_cart_message = NULL;

            $userMessage->save();
        }


        if ($user->shopping_cart === NULL) {
            return view('.users.showcart');
        }

        if (count($user->shopping_cart) === 0) {

            return view('users.showcart', [
                'empty_message' => 'Your shopping cart is currently empty'
            ]);
        } else {

            $cartBoards = Snowboard::whereIn('id', $user->shopping_cart)
                ->select('brand', 'model', 'image', 'id', 'price')
                ->get();

            $cartTotal = 0.00;

            foreach ($cartBoards as $cartBoard) {

                $cartTotal += $cartBoard->price;
            }

            $cartTotal = number_format($cartTotal, 2);

            return view('users.showcart', [

                'shopping_cart' => $cartBoards,
                'cart_total' => $cartTotal
            ]);
        }
    }

    public function createCart(Request $request)
    {
        $currentUser = Auth::user();

        $boardToAdd = $request->input('board');

        if (is_null($currentUser->shopping_cart)) {

            $user = User::where('id', '=', $currentUser->id)
                ->first();

            $user->shopping_cart = collect([$boardToAdd]);

            $user->save();
        } else {

            $user = User::where('id', '=', $currentUser->id)
                ->first();

            $collection = collect($user->shopping_cart);

            $collection->push($boardToAdd);

            $user->shopping_cart = $collection;

            $user->save();
        }

        return redirect('/users/cart');
    }

    public function destroyCartItem($id)
    {
        $authUserID = Auth::user()->id;

        $user = User::where('id', '=', $authUserID)
            ->select('shopping_cart')
            ->first();

        $collection = collect($user->shopping_cart);

        $shoppingCart = $collection->filter(function ($value, $key)  use ($id) {

            return $value !== $id;
        });

        User::where('id', '=', $authUserID)
            ->update(
                ['shopping_cart' => $shoppingCart]
            );

        return redirect('/users/cart')
            ->with('status', 'Item successfully removed');
    }

    public function showCheckout(Request $request)
    {
        return view('users.showcheckout', [

            'purchase_id' => $request->query('purchase'),
            'cart_total' => $request->query('total')
        ]);
    }

    public function createCheckout(Request $request)
    {
        $paymentMethod = $request->input('payment_id');

        $cardHolder = $request->input('card_holder');

        $total = $request->query('total');

        $purchaseId = $request->query('purchase');

        $user = User::where('name', '=', $cardHolder)
            ->where('id', '=', Auth::user()->id)
            ->first();

        if (!$user) {

            return redirect()
                ->back()
                ->with('match_not_found', 'Card is invalid');
        }

        $paymentStatus = NULL;

        $this->createPurchaseRecord($user->shopping_cart, $purchaseId);

        if ($this->updateBoardQuantity($user)) {

            $paymentStatus = $this->makePayment(
                $user,
                $total,
                $paymentMethod,
            );
        }

        if (isset($paymentStatus)) {

            return redirect('/users/paymentsuccess')
                ->with(
                    'success',
                    'Thank you for shopping with Board Reserve!'
                )
                ->with(
                    'email_sent',
                    'An order confirmation email will be sent to you shortly.'
                );
        }
    }

    private function UpdateBoardQuantity($buyer)
    {

        $boardIDs = collect($buyer->shopping_cart);

        $boards = Snowboard::whereIn('id', $boardIDs)
            ->get();

        foreach ($boards as $board) {

            if ($board->quantity <= 1) {

                $board->quantity = 0;
            } else {

                $board->quantity = $board->quantity - 1;
            }

            $board->save();
        }
        return true;
    }

    private function makePayment($user, $paymentTotal, $paymentMethod)
    {
        $user->stripe_id = NULL;

        if (strpos($paymentTotal, ',') > -1) {

            $paymentTotal = str_replace(',', '', $paymentTotal);
        }

        $user->charge(intval($paymentTotal * 100), $paymentMethod);

        $this->addToPurchases($user->shopping_cart);

        $user->shopping_cart = NULL;

        $user->stripe_id = $paymentMethod;

        $user->save();

        return true;
    }

    private function createPurchaseRecord($shoppingCart, $purchaseId)
    {

        $purchase = Purchase::select('*')
            ->where('id', '=', $purchaseId)
            ->first();

        $purchase->items_purchased = collect($shoppingCart);

        $this->sendConfirmationEmail($purchase);

        $purchase->save();
    }

    private function getBoardImagesForEmail($boards)
    {

        $snowboards = Snowboard::select('image', 'id', 'model', 'brand', 'price')
            ->whereIn('id', $boards)
            ->get();

        $modifiedSnowboards = [];

        foreach ($snowboards as $snowboard) {

            $modifiedSnowboard = array_combine(

                array_keys(
                    json_decode($snowboard, true)
                ),
                array_map(
                    function ($key, $value) {

                        if ($key === 'image') {

                            return Storage::disk('s3')->url($value);
                        } else {

                            return $value;
                        }
                    },
                    array_keys(
                        json_decode($snowboard, true)
                    ),
                    json_decode($snowboard, true)
                )
            );
            array_push($modifiedSnowboards, $modifiedSnowboard);
        }
        return $modifiedSnowboards;
    }

    private function getEstimatedDeliveryDate($purchasedItem)
    {
        $datePurchasedInSeconds = strtotime($purchasedItem->created_at);

        $totalSeconds = NULL;

        $shippingDaysInSeconds = NULL;

        $shippingPrice = NULL;

        if ($purchasedItem->shipping_method === '3-5 day') {

            $shippingDaysInSeconds = 345600;

            $totalSeconds = $shippingDaysInSeconds + $datePurchasedInSeconds;

            $shippingPrice = 6.99;
        } else if ($purchasedItem->shipping_method === '7 day') {

            $shippingDaysInSeconds = 777600;

            $totalSeconds = $shippingDaysInSeconds + $datePurchasedInSeconds;

            $shippingPrice = 2.99;
        } else if ($purchasedItem->shipping_method === '1 day') {

            $shippingDaysInSeconds = 86400;

            $totalSeconds = $shippingDaysInSeconds + $datePurchasedInSeconds;

            $shippingPrice = 12.95;
        }

        $nameOfDay = date('D', $totalSeconds);

        if ($nameOfDay === 'Sat') {

            $twoDays = 172800;

            $totalSeconds = $totalSeconds + $twoDays;

            $nameOfDay = 'Mon';
        } else if ($nameOfDay === 'Sun') {

            $oneDay = 86400;

            $totalSeconds = $totalSeconds + $oneDay;

            $nameOfDay = 'Mon';
        } else {

            $nameOfDay = date('D', $totalSeconds);
        }

        $year = date('Y', $totalSeconds);

        $month = date('M', $totalSeconds);

        $day = date('d', $totalSeconds);

        $estimatedDeliveryDate = $nameOfDay . ', ' . $month . ' ' . $day . ' ' . $year;

        $estimatedDeliveryDateText = 'Your Estimated Delivery Date is: ';

        $estimatedDelivery = $estimatedDeliveryDateText . $estimatedDeliveryDate;

        return [
            'estimated_delivery_date' => $estimatedDelivery,
            'shipping_price' => $shippingPrice
        ];
    }

    private function sendConfirmationEmail($purchase)
    {
        $snowboards = $this->getBoardImagesForEmail($purchase->items_purchased);

        $shipping = $this->getEstimatedDeliveryDate($purchase);

        Mail::to($purchase->email)
            ->send(new OrderConfirmation($purchase, $snowboards, $shipping));
    }

    private function addToPurchases($cart)
    {
        $AuthID = Auth::user()->id;

        $user = User::where('id', '=', $AuthID)
            ->select('id', 'purchases')
            ->first();

        $time = date("Y-m-d H:i:s", time());

        $assocCart = [];

        foreach ($cart as $item) {

            $assocCart[] = ['id' => $item, 'purchase_date' => $time];
        }

        $newCollection = collect($assocCart);

        $existingCollection = collect($user->purchases);

        $mergeCollections = $existingCollection->merge($newCollection);

        $mergeCollections->all();

        $user->purchases = $mergeCollections;

        $user->save();
    }

    public function showPaymentSuccess()
    {
        return view('users.showpaymentsuccess');
    }

    public function destroySnowboard(Request $request, $id)
    {
        Session::put('formID', $id);

        $validatedData = $request->validate(
            [

                'num_to_delete' => [
                    'required',
                    'numeric',
                    'between:1,' . $request->input('max')

                ]
            ]
        );

        $snowboard = Snowboard::where('id', '=', $id)
            ->select('quantity', 'id', 'image')
            ->first();

        if ($snowboard->quantity > 1) {

            $snowboard->quantity = ($snowboard->quantity - $request
                ->input('num_to_delete'));

            $snowboard->save();
        } else {

            if ($snowboard->quantity <= 1) {

                $usersWithCarts = User::whereNotNull('shopping_cart')
                    ->select('id', 'shopping_cart')
                    ->get();


                foreach ($usersWithCarts as $user) {

                    $collection = collect($user->shopping_cart);


                    $filteredCollection = $collection->filter(
                        function ($value, $key) use ($snowboard, $user) {

                            if (intval($value) !== intval($snowboard->id)) {

                                return $value;
                            } else {
                                $user->shopping_cart_message = 'An item(s) from your shopping cart has been removed because the owner took it down for sale';

                                $imageToDelete =  Snowboard::select('id', 'image')
                                    ->where('id', '=', $value)
                                    ->first();

                                Storage::disk('s3')->delete($imageToDelete->image);
                            }
                        }
                    );
                    $user->shopping_cart = $filteredCollection;

                    $user->save();
                }

                $snowboard->delete();
            }
        }
        return redirect('/users/snowboards')
            ->with('success', 'Snowboard deleted successfully');
    }


    private function getUsersRelatedBoards($boardID, $userID)
    {
        $boards = Snowboard::where('id', '!=', $boardID)
            ->where('seller', '=', $userID)
            ->get();

        return $boards;
    }

    private function formatUserJoinedDate($user)
    {
        $year = date('Y', strtotime($user->created_at));

        $month = date('M', strtotime($user->created_at));

        $joinedDate = $month . ', ' . $year;

        return $joinedDate;
    }

    public function showSnowboardUpdate($id)
    {
        $snowboard = Snowboard::select('*')
            ->where('id', '=', $id)
            ->first();

        if (!empty($snowboard->image)) {

            $prevImage = $this->downloadPrevImage($snowboard->image);
        }



        return view('users.showsnowboardupdate', [

            'snowboard' => $snowboard,
            'prevImage' => $prevImage ?? ''
        ]);
    }

    private function downloadPrevImage($image)
    {
        $image = Storage::disk('s3')->temporaryUrl(
            $image,
            now()->addHour(),
            ['ResponseContentDisposition' => 'attachment']
        );
        return $image;
    }

    public function updateSnowboard(Request $request, $id)
    {
        $validatedData = $request->validate(
            [
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
            ]
        );

        $file = $request->file('file');

        $snowboardToUpdate = Snowboard::select('*')
            ->where('id', '=', $id)
            ->first();

        if (!strpos(
            $snowboardToUpdate->image,
            $request->file('file')->getClientOriginalName()
        )) {

            Storage::disk('s3')->delete($snowboardToUpdate->image);
        }

        if (!is_null($file)) {
            $imageFilePath = $this->uploadPhotoToS3($file);

            $snowboardToUpdate->image = $imageFilePath;
        } else {
            $snowboardToUpdate->image = '';
        }

        $snowboardToUpdate->model = ucwords($request->input('model'));

        $snowboardToUpdate->category = ucwords($request->input('category'));

        $snowboardToUpdate->brand = ucwords($request->input('brand'));

        $snowboardToUpdate->size = $request->input('length');

        $snowboardToUpdate->desc = ucfirst($request->input('desc'));

        $snowboardToUpdate->quantity = $request->input('quantity');

        $snowboardToUpdate->price = $request->input('price');

        $snowboardToUpdate->shape = ucwords($request->input('shape'));

        $snowboardToUpdate->save();


        return redirect('/users/snowboards')
            ->with('update_success', 'The snowboard was updated');
    }

    private function  uploadPhotoToS3($file)
    {
        $filePath = time() . '_' . $file->getClientOriginalName();

        Storage::disk('s3')->put($filePath, fopen($file, 'r+'), 'public');

        return $filePath;
    }

    public function showPurchases()
    {
        $currentUserID = Auth::user()->id;

        $user = User::where('id', '=', $currentUserID)
            ->select('id', 'purchases')
            ->first();

        $snowboards = NULL;

        if (is_null($user->purchases)) {

            return view('users.showpurchases', [
                'snowboards' => null,
            ]);
        }

        foreach ($user->purchases as $purchase) {

            foreach ($purchase as $key => $value) {

                $purchase['isPurchaseOld'] = 'false';

                if ($key === 'purchase_date') {

                    $oneWeek = 604800;

                    $timeElapsed = time() - strtotime($value);

                    if ($timeElapsed > $oneWeek) {

                        $purchase['isPurchaseOld'] = 'true';
                    } else {

                        $snowboards[] = Snowboard::select('id', 'image', 'model', 'brand')
                            ->find($purchase['id']);
                    }
                }
            }
        }

        $collection = collect($snowboards);

        foreach ($collection as $snowboard) {

            foreach ($user->purchases as $purchase) {

                if (intval($purchase['id']) === intval($snowboard->id)) {

                    $snowboard->purchase_date =
                        $this->formatPurchaseDate($purchase['purchase_date']);
                }
            }
        }

        return view('users.showpurchases', [

            'snowboards' => $collection,

        ]);
    }

    private function formatPurchaseDate($timestamp)
    {
        $month = date('m', strtotime($timestamp));

        $day = date('d', strtotime($timestamp));

        $year = date('Y', strtotime($timestamp));

        $formattedPurchaseDate = $month . "/" . $day . "/" . $year;

        return $formattedPurchaseDate;
    }

    public function showChangePassword()
    {
        return view('users.showchangepassword', [

            'userId' => Auth::user()->id,
        ]);
    }

    public function updatePassword(Request $request, $id)
    {
        $oldPassword = $request->input('oldpassword');

        $newPassword = $request->input('newpassword');

        $confirmNewPassword = $request->input('confirmnewpassword');

        $request->validate(
            [

                'oldpassword' => ['required'],
                'newpassword' => ['required',  'min:6', 'max:25'],
                'confirmnewpassword' => ['required', 'min:6', 'max:25'],
            ]
        );

        $user = User::select('id', 'password')
            ->where('id', '=', $id)
            ->first();

        if (!Hash::check($oldPassword, $user->password)) {

            return redirect()
                ->back()
                ->with(
                    'password_invalid',
                    'Password does not match current password'
                );
        }

        if ($newPassword === $oldPassword) {

            return redirect()
                ->back()
                ->with(
                    'password_same',
                    'Password cannot be the same as old password'
                );
        }

        if ($newPassword !== $confirmNewPassword) {

            return redirect()
                ->back()
                ->with(
                    'new_and_confirm_not_match',
                    'New password and confirm password do not match'
                );
        }

        $user->password = Hash::make($newPassword);

        $user->save();

        $updatedUser = User::select('id', 'password')
            ->where('id', '=', Auth::user()->id)
            ->first();

        if (!Hash::check($oldPassword, $updatedUser->password)) {

            return redirect('/users/profile/me')
                ->with(
                    'password_updated',
                    'Your password has been changed'
                );
        }
    }

    public function showChangeUsername()
    {
        $user = User::select('id', 'username')
            ->where('id', '=', Auth::user()->id)
            ->first();

        return view('users.showchangeusername', [

            'user' => $user
        ]);
    }

    public function updateUsername(Request $request, $id)
    {
        $potentialUsername = $request->input('username');

        $allUsers = User::select('id', 'username')->get();

        foreach ($allUsers as $user) {
            if (
                strtolower($user->username) === strtolower($potentialUsername)
            ) {
                return redirect()
                    ->back()
                    ->with(
                        'username_taken',
                        'Username is already taken.'
                    );
            }
        }

        $request->validate(
            [
                'username' => [
                    'required',
                    'min:4',
                    'max:20',
                    'alpha_dash'
                ]
            ]
        );

        $user = User::select('id', 'username', 'username_updated')
            ->where('id', '=', Auth::user()->id)
            ->first();

        $lastChangeDate = $user->username_updated;

        $oneMonth = 2592000;

        $updateAvailable = false;


        if (time() - $lastChangeDate > $oneMonth) {

            $updateAvailable = true;
        }

        if (is_null($user->username_updated)) {

            $updateAvailable = true;
        }

        if (!$updateAvailable) {
            return redirect()
                ->back()
                ->with(
                    'time',
                    'You can only update your username once a month.'
                );
        } else {

            $user->username = $potentialUsername;

            $user->username_updated =  time();

            $this->updateReviewUsername($user, $potentialUsername);

            $user->save();

            return redirect('/users/profile/me')
                ->with(
                    'update_name_success',
                    'Username has been successfully updated'
                );
        }
    }

    private function updateReviewUsername($user, $newUsername)
    {
        $reviewsByUser = Review::select('userId', 'username')
            ->where('userId', '=', $user->id)
            ->update(['username' => $newUsername]);
    }

    public function showAllCurrentUserReviews()
    {
        $currentUserId = Auth::user()->id;

        $perPage = 3;

        $reviews = Review::select('*')
            ->where('userId', '=', $currentUserId)->paginate($perPage);

        $totalReviews = Review::where('userId', '=', $currentUserId)
            ->count();

        $pages = $totalReviews / $perPage;

        $numOfPages = NULL;

        if (!is_int($pages)) {

            $numOfPages = floor($pages + 1);
        } else {

            $numOfPages = $pages;
        }

        $boardIDs = [];

        foreach ($reviews as $review) {

            $boardIDs[] = $review->boardId;
        }

        $relatedSnowboards = Snowboard::select('id', 'seller', 'model', 'brand')
            ->whereIn('id', $boardIDs)
            ->get();

        foreach ($reviews as $review) {

            foreach ($relatedSnowboards as $relatedSnowboard) {

                if ($review->boardId === $relatedSnowboard->id) {

                    $review->model = $relatedSnowboard->model;

                    $review->brand = $relatedSnowboard->brand;
                }
            }
        }

        return view('users.showallcurrentuserreviews', [

            'reviews' => $reviews,
            'total_reviews' => $totalReviews,
            'num_of_pages' => $numOfPages

        ]);
    }

    public function indexUsers()
    {
        $currentUserID = Auth::user()->id;

        $perPage = 3;

        $currentUser = User::select('id', 'following')
            ->where('id', '=', $currentUserID)
            ->first();

        $numberOfUsers = User::where('id', '!=', $currentUserID)
            ->count();

        $totalPages = $numberOfUsers / $perPage;

        if (!is_int($totalPages)) {

            $totalPages = ceil($totalPages);
        }

        $users = User::select('image', 'id', 'username')
            ->where('id', '!=', $currentUserID)
            ->paginate($perPage);

        return view('users.indexusers', [
            'users' => $users,
            'current_user' => $currentUser,
            'total_pages' => $totalPages

        ]);
    }

    public function updateAddToFollowingList($id)
    {
        $authID = Auth::user()->id;

        $currentUser = User::select('*')
            ->where('id', '=', $authID)
            ->first();

        $userToBeFollowed = User::select('*')
            ->where('id', '=', $id)
            ->first();

        $followingCollection = NULL;

        if (is_null($currentUser->following)) {

            $followingCollection = collect([$id]);
        } else {

            $followingCollection = collect($currentUser->following);

            $followingCollection->push($id);

            $followingCollection->all();
        }

        $currentUser->following = $followingCollection;

        $currentUser->save();

        $this->addFollowerToFollowerList(
            $userToBeFollowed,
            $currentUser->id
        );

        return redirect()
            ->back()
            ->with(
                'now_following',
                'You are now following ' . $userToBeFollowed->username
            );
    }

    private function addFollowerToFollowerList($user, $id)
    {

        $followerCollection = NULL;

        if (is_null($user->followers)) {

            $followerCollection = collect([strval($id)]);
        } else {

            $followerCollection = collect($user->followers);

            $followerCollection->push($id);

            $followerCollection->all();
        }

        $user->followers = $followerCollection;

        $user->save();
    }

    public function updateRemoveFromFollowingList($id)
    {
        $authID = Auth::user()->id;

        $currentUser = User::select('*')
            ->where('id', '=', $authID)
            ->first();

        $userToBeUnFollowed = User::select('*')
            ->where('id', '=', $id)
            ->first();

        $followingCollection = collect($currentUser->following);

        $filteredFollowing  = $followingCollection
            ->filter(
                function ($value, $key) use ($id) {

                    return strval($value) !== strval($id);
                }
            );

        $currentUser->following = $filteredFollowing;

        $this->removeFromFollowerList($currentUser, $userToBeUnFollowed);

        $currentUser->save();

        return redirect()
            ->back()
            ->with(
                'unfollow',
                'You have unfollowed ' . $userToBeUnFollowed->username
            );
    }

    private function removeFromFollowerList($currentUser, $userToBeUnFollowed)
    {
        $followerCollection = collect($userToBeUnFollowed->followers);

        $filteredFollowerCollection = $followerCollection
            ->filter(
                function ($value, $key) use ($currentUser) {

                    return strval($value) !== strval($currentUser->id);
                }
            );
        if (count($filteredFollowerCollection) <= 0) {

            $userToBeUnFollowed->followers = NULL;
        } else {

            $userToBeUnFollowed->followers = $filteredFollowerCollection;
        }

        $userToBeUnFollowed->save();
    }

    public function showFollowing()
    {

        $currentUser = User::select('id', 'following')
            ->where('id', '=', Auth::user()->id)
            ->first();

        if ($currentUser->following === NULL) {

            return view('users.showfollowing', [
                'following_users' => [],
            ]);
        }

        $followingUsers = User::select('id', 'username', 'image', 'following', 'followers')
            ->whereIn('id', $currentUser->following)
            ->get();



        return view('users.showfollowing', [
            'following_users' => $followingUsers

        ]);
    }

    public function updateRemoveFromFollowingPage($id)
    {

        $currentUser = User::select('*')
            ->where('id', '=', Auth::user()->id)
            ->first();

        $currentUserID = $currentUser->id;

        $userBeingUnfollowed = User::select('*')
            ->where('id', '=', $id)
            ->first();

        $followingCollection = collect($currentUser->following);

        $userBeingUnfollowedCollection = collect($userBeingUnfollowed->followers);


        $filteredUnfollowing = $this->filterCollection($userBeingUnfollowedCollection, $currentUserID);

        $filteredFollowing = $this->filterCollection($followingCollection, $id);




        if (count($filteredFollowing) === 0) {

            $currentUser->following = NULL;
        } else {

            $currentUser->following = $filteredFollowing;
        }

        if (count($filteredUnfollowing) === 0) {

            $userBeingUnfollowed->followers = NULL;
        } else {

            $userBeingUnfollowed->followers = $filteredUnfollowing;
        }

        $userBeingUnfollowed->save();
        $currentUser->save();


        return redirect()
            ->back()
            ->with(
                'unfollowed',
                'You unfollowed ' . $userBeingUnfollowed->username
            );
    }


    private function filterCollection($collection, $target)
    {
        return $collection->filter(

            function ($value, $key) use ($target) {

                return strval($value) !== strval($target);
            }
        );
    }

    public function showFeed()
    {
        $currentUser = User::select('id', 'following', 'name')
            ->where('id', '=', Auth::user()->id)
            ->first();

        $firstName = explode(' ', $currentUser->name)[0];

        if (!$currentUser->following) {

            return view('users.showfeed', [

                'snowboards' => [],
                'first_name' => $firstName
            ]);
        }


        $snowboards = Snowboard::select('*')
            ->whereIn('seller', $currentUser->following)
            ->orderBy('created_at', 'desc')
            ->get();


        foreach ($snowboards as $snowboard) {

            $user = User::select('image', 'username')
                ->where('id', '=', $snowboard->seller)
                ->first();

            $snowboard->profilepic = $user->image;

            $snowboard->readable_date = $this->formatFeedPostedDate($snowboard);

            $snowboard->username = $user->username;
        }


        return view('users.showfeed', [

            'snowboards' => $snowboards,
            'first_name' => $firstName
        ]);
    }

    private function formatFeedPostedDate($snowboard)
    {

        $year = date('Y', strtotime($snowboard->created_at));

        $dayName = date('D', strtotime($snowboard->created_at));

        $dayNumber = date('d', strtotime($snowboard->created_at));

        $month = date('M', strtotime($snowboard->created_at));

        $time = date('h:i a', strtotime($snowboard->created_at));

        $timestamp = strtotime($snowboard->created_at);

        $postedText = NULL;

        if (time() - $timestamp < 86400) {

            $postedText = 'Posted Today ';
        } else {
            $postedText = 'Posted on ';
        }

        return $postedText . $time . ' ' . $dayName . ' ' . $month . ' ' . $dayNumber . ' ' . $year;
    }
}
