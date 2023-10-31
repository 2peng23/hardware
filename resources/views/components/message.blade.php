@if (session('message'))
    <div id="messageModal" class="messageModal">
        <!-- Modal content -->
        <div class="message-modal-content animate__animated animate__bounceIn rounded">
            <p class="text-success fs-5 fw-bolder ">{{ session('message') }}</p>
            <span class="message-close">
                <i class="fa fa-check-circle fs-1 text-success animate__zoomIn animate__animated"></i>
            </span>
        </div>

    </div>
@endif
@if (session('error'))
    <div id="messageModal" class="messageModal">
        <!-- Modal content -->
        <div class="message-modal-content animate__animated animate__bounceIn rounded">
            <p class="text-danger fs-5 fw-bolder ">{{ session('error') }}</p>
            <span class="message-close">
                <i class="fa fa-times-circle fs-1 text-danger animate__zoomIn animate__animated"></i>
            </span>
        </div>

    </div>
@endif
