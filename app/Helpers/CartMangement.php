<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Support\Facades\Cookie;

class CartMangement
{

  // add items to cart
  static public function addItemsToCart($productId) : int
  {
    $cardItems = self::getCartItemsFromCookie();
    
    $existingItem = null;

    foreach ($cardItems as $key => $item) {
      if ($item['product_id'] === $productId) {
        $existingItem = $key;
        break;
      }
    }

    if ($existingItem !== null) {

      $cardItems[$existingItem]['quantity']++;
      $cardItems[$existingItem]['total_amount'] = $cardItems[$existingItem]['unit_amount'] * $cardItems[$existingItem]['quantity'];

    } else  {

      $product = Product::where('id', $productId)->firstOrFail(['id', 'name', 'price', 'images']);

      $cardItems[] = [
        'product_id'    => $productId,
        'name'          => $product->name,
        'quantity'      => 1,
        'image'         => $product->images[0],
        'price'         => $product->price,
        'unit_amount'   => $product->price,
        'total_amount'  => $product->price,
      ];
      
    }

    self::addCardItemsToCookie($cardItems);

    return count($cardItems);
  }
  // remove items from carts
  static public function removeCartItem($productId) : array
  {
    $cardItems = self::getCartItemsFromCookie();
    
    foreach ($cardItems as $key => $item) {
      if ($item['product_id'] === $productId) {
        unset($cardItems[$key]);
      }
    }

    self::addCardItemsToCookie($cardItems);

    return $cardItems;
  }

  // add items to cookie
  static public function addCardItemsToCookie($cardItems)
  {
    Cookie::queue('card_items', json_encode($cardItems), 60 * 24 * 30);
  }

  // clear cart items from cookie
  static public function clearCardItems()
  {
    Cookie::queue(Cookie::forget('card_items'));
  }


  // get all items from cookie
  static public function getCartItemsFromCookie()
  {
    $cardItems = json_decode(Cookie::get('card_items'), true);

    if(!$cardItems) {
      $cardItems = [];
    }
    
    return $cardItems;
  }


  // increment item quantity
  static public function incrementQuantityToCartItems($productId) : array
  {
    $cardItems = self::getCartItemsFromCookie();

    foreach ($cardItems as $key => $item) {
      if ($item['product_id'] == $productId) {
        $cardItems[$key]['quantity']++;
        $cardItems[$key]['total_amount'] = $cardItems[$key]['unit_amount'] * $cardItems[$key]['quantity'];
        break;
      }
    }

    self::addCardItemsToCookie($cardItems);

    return $cardItems;
  }

  // decrement item quantity
  static public function decrementQuantityToCartItems($productId) : array
  {
    $cardItems = self::getCartItemsFromCookie();

    if (count($cardItems) > 1) {

      foreach ($cardItems as $key => $item) {
        if ($item['product_id'] == $productId) {
          $cardItems[$key]['quantity']--;
          $cardItems[$key]['total_amount'] = $cardItems[$key]['unit_amount'] * $cardItems[$key]['quantity'];
          break;
        }
      }
      
      self::addCardItemsToCookie($cardItems);
      return $cardItems;
    }

  }

  // calculate grand total price
  static public function calculateGrandTotal($items)
  {
    return array_sum(array_column($items, 'total_amount'));
  }

  // add items to cart with qty

   static public function addItemsToCartWithQty($productId, $qty = 1) : int
  {
    $cardItems = self::getCartItemsFromCookie();
    
    $existingItem = null;

    foreach ($cardItems as $key => $item) {
      if ($item['product_id'] === $productId) {
        $existingItem = $key;
        break;
      }
    }

    if ($existingItem !== null) {

      $cardItems[$existingItem]['quantity'] = $qty;
      $cardItems[$existingItem]['total_amount'] = $cardItems[$existingItem]['unit_amount'] * $cardItems[$existingItem]['quantity'];

    } else  {

      $product = Product::where('id', $productId)->firstOrFail(['id', 'name', 'price', 'images']);

      $cardItems[] = [
        'product_id'    => $productId,
        'name'          => $product->name,
        'quantity'      => 1,
        'image'         => $product->images[0],
        'price'         => $product->price,
        'unit_amount'   => $product->price,
        'total_amount'  => $product->price,
      ];
      
    }

    self::addCardItemsToCookie($cardItems);

    return count($cardItems);
  }

}