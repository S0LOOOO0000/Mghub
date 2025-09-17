<!-- Request QR Scanner Modal -->
<div id="requestQrModal">
    <div class="modal-content">
        <span id="closeRequestQrModal" class="close">&times;</span>
        <h3 id="requestQrModalTitle">Scan QR Code</h3>
        <select id="cameraSelect" style="margin-bottom:10px;width:100%;padding:5px;"></select>
        <div id="request-qr-reader"></div>
    </div>
</div>

<!-- Optional: Result modal -->
<div id="requestResultModal" class="attendance-modal">
    <div class="modal-box">
        <h3 id="requestResultMessage"></h3>
        <p id="requestResultDetails"></p>
    </div>
</div>

<!-- Styles (if not using external CSS) -->
<style>
#requestQrModal {
    display: none;
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.8);
    z-index:10000;
    justify-content:center;
    align-items:center;
}
#requestQrModal .modal-content {
    background:#fff;
    padding:20px;
    border-radius:12px;
    width:500px;
    max-width:90%;
    text-align:center;
}
#request-qr-reader {
    width:100%;
    height:400px;
    margin:10px auto;
}
#request-qr-reader video {
    width:100% !important;
    height:100% !important;
    object-fit:cover;
    border-radius:8px;
}
.close {
    float:right;
    font-size:22px;
    cursor:pointer;
}
.attendance-modal {
    display:none;
    position: fixed;
    top:0; left:0;
    width:100%; height:100%;
    background: rgba(0,0,0,0.8);
    justify-content:center;
    align-items:center;
    z-index:2000;
}
.attendance-modal .modal-box {
    background:#fff;
    padding:20px;
    border-radius:10px;
    text-align:center;
    max-width:350px;
}
.attendance-modal.success .modal-box { border: 2px solid green; }
.attendance-modal.error .modal-box { border: 2px solid red; }
</style>
