<!-- Large modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#@yield('modal-id', 'modalId')
        ">@yield('modal-button', 'view' )</button>

<div class="modal fade" id="@yield('modal-id', 'modalId')" tabindex="-1" role="dialog"
     aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                </button>
                <h2 class="modal-title" id="@yield('modal-label', 'myModalLabel')">
                </h2>
            </div>

            <div class="modal-body-container">
                <div class="modal-nav col-xs-3">
                    <!-- required for floating -->
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tabs-left">
                        @yield('modal-nav')

                    </ul>
                </div>
                <div class="modal-body col-xs-9">
                    <div class="tab-content">
                        @yield('modal-content')

                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="modal-footer">
                @yield('modal-buttons')
            </div>

        </div>
    </div>
</div>