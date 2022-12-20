@extends('meganet.layout.master')
@section('title') @lang('translation.Dashboard') @endsection

@section('content')
    <Breadcrumb
        :list=[{title:"Pagina"},{title:"Configuracion",active:"active"}]
    ></Breadcrumb>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row justify-content-center mt-3">
                        <div class="col-xl-5 col-lg-8">
                            <div class="text-center">
                                <h5>Configuracion</h5>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <hr>
                    <!-- end row -->

                    <div class="row d-flex">
                        <div style="width: 250px">
                            <div class="card cursor-pointer bx-tada-hover">
                                <!-- Modal -->
                                <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#debtpaymentclient">
                                    <div class="card-body overflow-hidden position-relative">
                                        <div>
                                            <i class="bx bx-help-circle widget-box-1-icon text-primary"></i>
                                        </div>
                                        <div class="faq-count d-flex">
                                            <h5 class="text-primary m-0"><i class="fa fa-fw fa-2x circle-icon fa-user"></i></h5> <span class="ms-1 align-self-center">Pago de deudas para clientes recurrentes</span>
                                        </div>
                                    </div>
                                </a>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                        <div style="width: 250px">
                            <div class="card cursor-pointer bx-tada-hover">
                                <a href="{{ url('/configuracion/debitcustom') }}">
                                    <div class="card-body overflow-hidden position-relative">
                                        <div>
                                            <i class="bx bx-help-circle widget-box-1-icon text-primary"></i>
                                        </div>
                                        <div class="faq-count d-flex">
                                            <h5 class="text-primary m-0"><i class="fa fa-fw fa-2x circle-icon fa-user-secret"></i></h5> <span class="ms-1 align-self-center">Pago de deudas para clientes custom</span>
                                        </div>
                                    </div>
                                </a>
                                <!-- end card body -->
                            </div>
                            <!-- end card -->
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- end  card body -->
            </div>
            <!-- end card -->
        </div>
        <!-- end col -->
    </div>
    <debt-payment-client id="debtpaymentclient"></debt-payment-client>
@endsection
