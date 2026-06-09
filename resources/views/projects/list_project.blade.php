@extends('layouts.simple.master')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/vendors/animate.css') }}">
@endsection

@section('main_content')
<div class="container-fluid">
    <div class="page-title">
      <div class="row g-2">
        <div class="col-sm-6">
          <h4>Project List </h4>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"> <a href="{{ route('admin.default_dashboard') }}">                                       
                <svg class="stroke-icon">
                  <use href="{{ asset('assets/svg/icon-sprite.svg#stroke-home') }}"></use>
                </svg></a></li>
            <li class="breadcrumb-item">Project</li>
            <li class="breadcrumb-item active">Project List</li>
          </ol>
        </div>
      </div>
    </div>
  </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row project-cards">
            <div class="col-md-12 project-list">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="nav nav-tabs border-tab" id="top-tab" role="tablist">
                                <li class="nav-item"><a class="nav-link active" id="top-home-tab" data-bs-toggle="tab"
                                        href="#top-home" role="tab" aria-controls="top-home" aria-selected="true"><i
                                            data-feather="target"></i>All</a></li>
                                <li class="nav-item"><a class="nav-link" id="profile-top-tab" data-bs-toggle="tab"
                                        href="#top-profile" role="tab" aria-controls="top-profile"
                                        aria-selected="false"><i data-feather="info"></i>Doing</a></li>
                                <li class="nav-item"><a class="nav-link" id="contact-top-tab" data-bs-toggle="tab"
                                        href="#top-contact" role="tab" aria-controls="top-contact"
                                        aria-selected="false"><i data-feather="check-circle"></i>Done</a></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-0 me-0"></div><a class="btn btn-primary" href="{{ route('admin.create_project') }}"> <i
                                    data-feather="plus-square"> </i>Create New Project</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content" id="top-tabContent">
                            <div class="tab-pane fade show active" id="top-home" role="tabpanel"
                                aria-labelledby="top-home-tab">
                                <div class="row">
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-primary"> <span
                                                class="badge badge-primary">Doing</span>
                                            <h5 class="f-w-500">Endless admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia </p>
                                                </div>
                                            </div>
                                            <p>Endless Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Universal admin Design </h5>
                                            <div class="d-flex"> <img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Universal Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Poco admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Poco Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Universal admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Universal Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-primary"><span
                                                class="badge badge-primary">Doing</span>
                                            <h5 class="f-w-500">Zeta admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Zeta Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Poco admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Poco Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="top-profile" role="tabpanel"
                                aria-labelledby="profile-top-tab">
                                <div class="row">
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-primary"><span
                                                class="badge badge-primary">Doing</span>
                                            <h5 class="f-w-500">Endless admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Endless Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-primary"><span
                                                class="badge badge-primary">Doing</span>
                                            <h5 class="f-w-500">Universal admin Design</h5>
                                            <div class="d-flex"> <img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Universal Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-primary"><span
                                                class="badge badge-primary">Doing</span>
                                            <h5 class="f-w-500">Poco admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>poco Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-primary"><span
                                                class="badge badge-primary">Doing</span>
                                            <h5 class="f-w-500">Tivo admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Tivo Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-primary"><span
                                                class="badge badge-primary">Doing</span>
                                            <h5 class="f-w-500">Enzo admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Enzo Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-primary"><span
                                                class="badge badge-primary">Doing</span>
                                            <h5 class="f-w-500">Xolo admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Xolo Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 font-primary">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 font-primary">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 font-primary">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-primary" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="top-contact" role="tabpanel"
                                aria-labelledby="contact-top-tab">
                                <div class="row">
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Endless admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Endless Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Universal admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Universal Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Poco admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>poco Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Universal admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>Universal Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">24</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">24</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">40</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+3 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Endless admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Themeforest, australia</p>
                                                </div>
                                            </div>
                                            <p>Endless Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">12 </div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">5</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">7</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+10 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>70% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success progress-bar-striped"
                                                        role="progressbar" style="width: 70%" aria-valuenow="10"
                                                        aria-valuemin="0" aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-xxl-4 col-lg-6 box-col-6">
                                        <div class="project-box b-light1-success"><span
                                                class="badge badge-success">Done</span>
                                            <h5 class="f-w-500">Poco admin Design</h5>
                                            <div class="d-flex"><img class="img-20 me-1 rounded-circle"
                                                    src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                    data-original-title="" title="">
                                                <div class="flex-grow-1">
                                                    <p>Envato, australia</p>
                                                </div>
                                            </div>
                                            <p>poco Admin is a full featured, multipurpose, premium bootstrap admin
                                                template.</p>
                                            <div class="row details">
                                                <div class="col-6"><span>Issues </span></div>
                                                <div class="col-6 text-success">40</div>
                                                <div class="col-6"> <span>Resolved</span></div>
                                                <div class="col-6 text-success">40</div>
                                                <div class="col-6"> <span>Comment</span></div>
                                                <div class="col-6 text-success">20</div>
                                            </div>
                                            <div class="customers">
                                                <ul>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/3.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/5.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block"><img class="img-30 rounded-circle"
                                                            src="{{ asset('assets/images/user/1.jpg') }}" alt=""
                                                            data-original-title="" title=""></li>
                                                    <li class="d-inline-block ms-2">
                                                        <p class="f-12">+2 More</p>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="project-status mt-4">
                                                <div class="d-flex mb-0">
                                                    <p>100% </p>
                                                    <div class="flex-grow-1 text-end"><span>Done</span></div>
                                                </div>
                                                <div class="progress" style="height: 5px">
                                                    <div class="progress-bar-animated bg-success" role="progressbar"
                                                        style="width: 100%" aria-valuenow="10" aria-valuemin="0"
                                                        aria-valuemax="100"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
@endsection

@section('scripts')
    <script src="{{ asset('assets/js/slick/slick.min.js') }}"></script>
    <script src="{{ asset('assets/js/slick/slick.js') }}"></script>
    <script src="{{ asset('assets/js/header-slick.js') }}"></script>
    <!-- calendar js-->
    <script src="{{ asset('assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead/typeahead.custom.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/handlebars.js') }}"></script>
    <script src="{{ asset('assets/js/typeahead-search/typeahead-custom.js') }}"></script>
@endsection
