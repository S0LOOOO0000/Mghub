<!-- QRcode modal -->
<div id="qrModal">
  <div class="modal-content">
    <span id="closeQrModal" class="close">&times;</span>
    <h3 id="qrModalTitle">Scan QR Code</h3>
    <div id="qr-reader"></div>
  </div>
</div>

<!-- attendance modal -->

<div id="attendanceModal" class="attendance-modal">
  <div class="modal-box">
    <h3 id="attendanceMessage"></h3>
    <p id="attendanceDetails"></p>
  </div>
</div>


<!-- ✅ Styles -->
<style>
  /*QRcode Style*/
#qrModal {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.8);
  z-index: 10000;
  justify-content: center;
  align-items: center;
}
.modal-content {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  width: 500px;
  max-width: 90%;
  text-align: center;
}
#qr-reader {
  width: 100%;
  height: 400px;
  margin: 10px auto;
}
#qr-reader video {
  width: 100% !important;
  height: 100% !important;
  object-fit: cover;
  border-radius: 8px;
}
.close {
  float: right;
  font-size: 22px;
  cursor: pointer;
}


  /*Attendance Style*/
.attendance-modal {
  display: none;
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: rgba(0,0,0,0.8);
  justify-content: center;
  align-items: center;
  z-index: 2000;
}
.attendance-modal .modal-box {
  background: #fff;
  padding: 20px;
  border-radius: 10px;
  text-align: center;
  max-width: 350px;
}
.attendance-modal.success .modal-box { border: 2px solid green; }
.attendance-modal.error .modal-box { border: 2px solid red; }
</style>

  <script src="../js/attendance.js"> </script>