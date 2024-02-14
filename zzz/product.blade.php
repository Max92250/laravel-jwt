
@extends('product.index')


@section('section')

<div id="header-carousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active" style="height: 410px;">
            <img class="img-fluid" src="https://www.apple.com/newsroom/images/tile-images/Apple_iphone_11-rosette-family-lineup-091019.jpg.landing-big_2x.jpg" alt="Image">
            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                <div class="p-3" style="max-width: 700px;">
                    <h4 class="text-light text-uppercase f
                    ont-weight-medium mb-3">10% Off Your First Order</h4>
                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Fashionable Dress</h3>
                    <a href="" class="btn btn-light py-2 px-3">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="carousel-item" style="height: 410px;">
            <img class="img-fluid" src="https://i.ytimg.com/vi/1aqI7EnfbVM/maxresdefault.jpg" alt="Image">
            <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                <div class="p-3" style="max-width: 700px;">
                    <h4 class="text-light text-uppercase font-weight-medium mb-3">10% Off Your First Order</h4>
                    <h3 class="display-4 text-white font-weight-semi-bold mb-4">Reasonable Price</h3>
                    <a href="" class="btn btn-light py-2 px-3">Shop Now</a>
                </div>
            </div>
        </div>
    </div>
    <a class="carousel-control-prev" href="#header-carousel" data-slide="prev">
        <div class="btn btn-dark" style="width: 45px; height: 45px;">
            <span class="carousel-control-prev-icon mb-n2"></span>
        </div>
    </a>
    <a class="carousel-control-next" href="#header-carousel" data-slide="next">
        <div class="btn btn-dark" style="width: 45px; height: 45px;">
            <span class="carousel-control-next-icon mb-n2"></span>
        </div>
    </a>
</div>
<div class="container-fluid pt-5">
    <div class="popupBox">
        <div class="popupBox__content">
          <div class="close"></div>
          <div class="popupBox__img">
            <img src="https://puls-img.chanel.com/1658828766814-8821913321502webp_819x768.webp" alt="" />
          </div>
          <div class="popupBox__contentTwo">
            <div>
              <h3 class="popupBox__title">Great Offer</h3>
              <h2 class="popupBox__titleTwo">60 <sup>%</sup><span> Off</span></h2>
              <p class="popupBox__description">
                Lorem ipsum dolor sit amet consectetur, adipisicing elit.
              </p>
              <a href="""  class="popupBox__btn">Get The Deal</a>
            
            </div>
          </div> 
        </div>
      </div>
    <div class="row px-xl-5 pb-3">
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fa fa-check text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">Quality Product</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fa fa-shipping-fast text-primary m-0 mr-2"></h1>
                <h5 class="font-weight-semi-bold m-0">Free Shipping</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fas fa-exchange-alt text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">14-Day Return</h5>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 col-sm-12 pb-1">
            <div class="d-flex align-items-center border mb-4" style="padding: 30px;">
                <h1 class="fa fa-phone-volume text-primary m-0 mr-3"></h1>
                <h5 class="font-weight-semi-bold m-0">24/7 Support</h5>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid pt-5">
    <div class="row px-xl-5 pb-3">
        
        <div class="col-lg-4 col-md-6 pb-1">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">4 Products</p>
                <a href="{{ route('products.by.category', ['categoryid' => 5]) }}" class="cat-img position-relative  mb-3">
                    <img class="img-fluid" src="https://onsalesoffers.com/wp-content/uploads/2023/06/iphjon.png" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">smartphones</h5>
            </div>
        </div>
        
        <div class="col-lg-4 col-md-6 pb-1">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">4 Products</p>
                <a  href="{{ route('products.by.category', ['categoryid' => 4]) }}"   class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="https://www.shutterstock.com/image-photo/kiev-ukraine-october-13-2017-260nw-776440144.jpg" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">laptops</h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 pb-1">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">4 Products</p>
                <a href=""  class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="https://puls-img.chanel.com/1658828766814-8821913321502webp_819x768.webp" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">fragrances</h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 pb-1">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">4 Products</p>
                <a  href=""  class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="https://media.allure.com/photos/654d3ac4767ebef727e25ac1/master/w_1280%2Cc_limit/chanel.jpg" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">skincare</h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 pb-1">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">4 Products</p>
                <a  href="" class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="https://a.1stdibscdn.com/chanel-vintage-white-logo-silk-mini-dress-for-sale/1121189/v_70324521566030834706/7032452_master.jpg?width=768" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">womens-dresses</h5>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 pb-1">
            <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
                <p class="text-right">4 Products</p>
                <a  href="" class="cat-img position-relative overflow-hidden mb-3">
                    <img class="img-fluid" src="https://en.louisvuitton.com/images/is/image/lv/1/PP_VP_L/louis-vuitton-laureate-platform-desert-boot--AQ8Q1BTX02_PM2_Front%20view.png?wid=490&hei=490" alt="">
                </a>
                <h5 class="font-weight-semi-bold m-0">womens-shoes</h5>
            </div>
        </div>
       
    </div>
</div>
<div class="container-fluid pt-5">
    <div class="text-center mb-4">
        <h2 class="section-title px-5"><span class="px-2">Trandy Mens</span></h2>
    </div>
    <div class="row px-xl-5 pb-3">
    <div class="col-lg-4 col-md-6 pb-1">
        <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
            <p class="text-right">4 Products</p>
            <a href="" class="cat-img position-relative overflow-hidden mb-3">
                <img class="img-fluid" src="https://cdn2.chrono24.com/images/uhren/21534832-i7set8koq40hk7bpivqdzaw1-ExtraLarge.jpg" alt="">
            </a>
            <h5 class="font-weight-semi-bold m-0">mens-watches</h5>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 pb-1">
        <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
            <p class="text-right">4 Products</p>
            <a href="" class="cat-img position-relative overflow-hidden mb-3">
                <img class="img-fluid" src="https://eu.louisvuitton.com/images/is/image/lv/1/PP_VP_L/louis-vuitton-inside-out-t-shirt-ready-to-wear--HIY47WJYN002_PM2_Front%20view.jpg" alt="">
            </a>
            <h5 class="font-weight-semi-bold m-0">mens-tshirt</h5>
        </div>
    </div>
    <div class="col-lg-4 col-md-6 pb-1">
        <div class="cat-item d-flex flex-column border mb-4" style="padding: 30px;">
            <p class="text-right">4 Products</p>
            <a href="" class="cat-img position-relative overflow-hidden mb-3">
                <img class="img-fluid" src="https://cdn.allsquaregolf.com/pictures/pictures/000/170/083/large/picture_product_undefined.jpg" alt="">
            </a>
            <h5 class="font-weight-semi-bold m-0">men shoes</h5>
        </div>
    </div>
    </div>
    
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <script>

const popup = document.querySelector(".popupBox");
const close = document.querySelector(".close");

window.onload = function () {
  setTimeout(() => {
    popup.style.display = "block";
  }, 3000);
};

close.addEventListener("click", () => {
  popup.style.display = "none";
});



</script>
</div>
@endsection

 