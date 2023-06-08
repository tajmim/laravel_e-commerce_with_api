<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  </head>
  <body>
    <x-admin-layout>
    </x-admin-layout>

    <div class="container">
        <h1>Add Product</h1>


        <form action=" {{url('submit_product')}} "  method="POST">

          @csrf
            <div class="mb-3">
              <label for="ProductName" class="form-label">Product Name</label>
              <input type="text" class="form-control" id="ProductName" placeholder="Laptop/Mouse/Keyboard....." name="product_name">
            </div>
            <div class="mb-3">
              <label for="ProductDes" class="form-label">Description</label>
              <input type="text" class="form-control" id="productDes" placeholder="Description" name="product_des">
            </div>
            <div class="mb-3">
              <label for="ProductPrice" class="form-label">Product Price</label>
              <input type="number" class="form-control" id="product_price" placeholder="123" name="product_price">
            </div>
            <div class="mb-3">
              <input type="submit" value="Add product" class="btn bg-primary">
            </div>


        </form>




    </div>
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
  </body>
</html>