<div class="col-lg-3">
                    <div class="binduz-er-sidebar-about">
                        <div class="binduz-er-sidebar-title">
                            <h4 class="binduz-er-title">{{ __('ui.sections.about_icmi') }}</h4>
                        </div>
                        <div class="binduz-er-sidebar-about-item">
                            <div class="binduz-er-sidebar-about-user d-flex">
                                <div class="binduz-er-thumb">
                                    <img src="{{ asset('assets/images/user.jpg') }}" alt="">
                                </div>
                                <div class="binduz-er-content">
                                    <h4 class="binduz-er-title">ICMI Kaltim</h4>
                                    <span>{{ __('ui.common.by') }}</span>
                                    <ul>
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-behance"></i></a></li>
                                        <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                                        <li><a href="#"><i class="fab fa-linkedin"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="binduz-er-text">
                                <p>{{ __('ui.sections.about_short_desc') }}</p>
                                <a class="binduz-er-main-btn" href="{{ route('sekilas-icmi') }}">{{ __('ui.common.read_more') }}</a>
                            </div>
                        </div>
                    </div>
                    <div class="binduz-er-sidebar-latest-post">
                        <div class="binduz-er-sidebar-title">
                            <h4 class="binduz-er-title">{{ __('ui.common.latest_post') }}</h4>
                        </div>
                        <div class="binduz-er-sidebar-latest-post-box">
                            <div class="binduz-er-sidebar-latest-post-item">
                                <div class="binduz-er-thumb">
                                    <img src="{{ asset('assets/images/latest-post-1.jpg') }}" alt="latest">
                                </div>
                                <div class="binduz-er-content">
                                    <span><i class="fal fa-calendar-alt"></i> 24th February 2020</span>
                                    <h4 class="binduz-er-title"><a href="#">Introducing Android Earthquake Alerts</a></h4>
                                </div>
                            </div>
                            <div class="binduz-er-sidebar-latest-post-item">
                                <div class="binduz-er-thumb">
                                    <img src="{{ asset('assets/images/latest-post-2.jpg') }}" alt="latest">
                                </div>
                                <div class="binduz-er-content">
                                    <span><i class="fal fa-calendar-alt"></i> 24th February 2020</span>
                                    <h4 class="binduz-er-title"><a href="#">Loud and clear: AI is improving Assistant </a></h4>
                                </div>
                            </div>
                            <div class="binduz-er-sidebar-latest-post-item">
                                <div class="binduz-er-thumb">
                                    <img src="{{ asset('assets/images/latest-post-3.jpg') }}" alt="latest">
                                </div>
                                <div class="binduz-er-content">
                                    <span><i class="fal fa-calendar-alt"></i> 24th February 2020</span>
                                    <h4 class="binduz-er-title"><a href="#">Tips and shortcuts for a more productive</a></h4>
                                </div>
                            </div>
                            <div class="binduz-er-sidebar-latest-post-item">
                                <div class="binduz-er-thumb">
                                    <img src="{{ asset('assets/images/latest-post-4.jpg') }}" alt="latest">
                                </div>
                                <div class="binduz-er-content">
                                    <span><i class="fal fa-calendar-alt"></i> 24th February 2020</span>
                                    <h4 class="binduz-er-title"><a href="#">Sparks of inspiration to the new trend 2021</a></h4>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="binduz-er-sidebar-add-box mt-40">
                        <div class="binduz-er-logo">
                            <a href="#"><img src="{{ asset('logo-icmi.png') }}" alt="Logo ICMI Kaltim"></a>
                        </div>
                        <p>{{ __('ui.footer.description') }}</p>
                        <a class="binduz-er-main-btn" href="{{ route('home') }}">{{ __('ui.common.open') }}</a>
                    </div>
                </div>
