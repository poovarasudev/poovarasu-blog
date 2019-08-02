<!-- For Modal Edit -->
<div class="modal fade" id="smallModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="smallModalLabel">Modal title</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger" id="modal-error-alert" style="display: none">
                </div>
                <form>
                    <div class="form-group">
                        <label for="title">User Name</label>
                        <input type="text" class="form-control" name="title" id="editUserId" disabled>
                    </div>
                    <div class="form-group">
                        <label for="name">Role Name</label><br>
                        <select class="form-control show-tick" data-live-search="true" id="editRoleId" name="rollName">
                            @foreach($roles as $role)
                                @if($role->name != 'admin')
                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="modal-close" data-dismiss="modal">
                        Close
                    </button>
                    <button class="btn btn-primary" id="update-btn" onclick="updateUserRole()">Update Role</button>
                </div>
            </div>
        </div>
    </div>
</div>