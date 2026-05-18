@extends('layouts.page')

@section('title', 'Contact - Binduz')
@section('body_class', 'gray-bg bg-2')
@section('top_header_cover_class', 'binduz-er-bg_cover')

@section('content')

    <div class="binduz-er-breadcrumb-area">
        <div class=" container">
            <div class="row">
                <div class=" col-lg-12">
                    <div class="binduz-er-breadcrumb-box">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Contact</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--====== BINDUZ HEADER PART ENDS ======-->

    <!--====== BINDUZ CONTACT MAP PART START ======-->
    <div class="binduz-er-map-area">
        <div class=" container">
            <div class="row">
                <div class=" col-lg-12">
                    <div class="binduz-er-map-box">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d41305.0575309695!2d90.40505299581727!3d23.78708119002918!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1612844343331!5m2!1sen!2sbd" width="100" height="600" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--====== BINDUZ CONTACT MAP PART ENDS ======-->

    <!--====== BINDUZ CONTACT US PART ENDS ======-->

    <div class="binduz-er-contact-us-area">
        <div class=" container">
            <div class="row">
                <div class=" col-lg-12">
                    <div class="binduz-er-contact-us-box">
                        <form action="#">
                            <div class="binduz-er-contact-title">
                                <h4 class="binduz-er-title">Get in touch & let us know</h4>
                            </div>
                            <div class="row">
                                <div class=" col-lg-4">
                                    <div class="binduz-er-input-box">
                                        <input type="text" placeholder="Enter full name">
                                        <i class="fal fa-user"></i>
                                    </div>
                                </div>
                                <div class=" col-lg-4">
                                    <div class="binduz-er-input-box">
                                        <input type="email" placeholder="Enter address">
                                        <i class="fal fa-envelope"></i>
                                    </div>
                                </div>
                                <div class=" col-lg-4">
                                    <div class="binduz-er-input-box binduz-er-select-item">
                                        <select>
                                            <option data-display="Select Subject">Web Development</option>
                                            <option value="1">Ui/Ux Designer</option>
                                            <option value="2">Another option</option>
                                            <option value="3">A disabled option</option>
                                            <option value="4">Potato</option>
                                        </select>
                                        <i class="fal fa-arrow-down"></i>
                                    </div>
                                </div>
                                <div class=" col-lg-12">
                                    <div class="binduz-er-input-box">
                                        <textarea name="#" id="#" cols="30" rows="10" placeholder="Enter message"></textarea>
                                        <i class="fal fa-pencil"></i>
                                    </div>
                                </div>
                            </div>
                            <button class="binduz-er-main-btn">Submit Request</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row pt-90">
                <div class=" col-lg-4">
                    <div class="binduz-er-contact-info-box">
                        <h3 class="binduz-er-title">Corporate Office</h3>
                        <ul>
                            <li>Address: <span>Lorem 142 Str., 2352, Pro State, USA</span></li>
                            <li>Phone: <span>+33-257634 534</span></li>
                            <li>Email: <span>info@webexample.com</span></li>
                        </ul>
                        <a class="binduz-er-main-btn" href="#">Make A Call</a>
                    </div>
                </div>
                <div class=" col-lg-4">
                    <div class="binduz-er-contact-info-box">
                        <h3 class="binduz-er-title">Main HQ</h3>
                        <ul>
                            <li>Address: <span>Lorem 142 Str., 2352, Pro State, USA</span></li>
                            <li>Phone: <span>+33-257634 534</span></li>
                            <li>Email: <span>info@webexample.com</span></li>
                        </ul>
                        <a class="binduz-er-main-btn active" href="#">Make A Call</a>
                    </div>
                </div>
                <div class=" col-lg-4">
                    <div class="binduz-er-contact-info-box">
                        <h3 class="binduz-er-title">Advertise</h3>
                        <ul>
                            <li>Address: <span>Lorem 142 Str., 2352, Pro State, USA</span></li>
                            <li>Phone: <span>+33-257634 534</span></li>
                            <li>Email: <span>info@webexample.com</span></li>
                        </ul>
                        <a class="binduz-er-main-btn" href="#">Make A Call</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--====== BINDUZ CONTACT US PART ENDS ======-->





    
@endsection

