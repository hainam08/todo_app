<!-- resources/views/admin/components/logout-modal.blade.php -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      
      <div class="modal-header">
        <h5 class="modal-title" id="logoutModalLabel">Xác nhận đăng xuất</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
      </div>

      <div class="modal-body">
        Bạn có chắc chắn muốn đăng xuất?
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Hủy</button>
        
        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-danger">Đăng xuất</button>
        </form>
      </div>
      
    </div>
  </div>
</div>
