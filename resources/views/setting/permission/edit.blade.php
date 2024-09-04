  <!-- Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-xl">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-title">Edit Permission</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <form method="POST" action="{{ route('admin.permission.update', $permission->id) }}">
                  @csrf @method('PUT')
                  <div class="modal-body">
                      <div class="row">
                          <input type="hidden" name="module" value="{{ $permission->module }}">
                          <div class="col-md-12">
                              <label for="name" class="form-label required">Name </label>
                              <input type="search" name="name" id="name" value="{{ $permission->name }}"
                                  class="form-control" required />
                              @if ($errors->has('name'))
                                  <div class="alert alert-danger">{{ $errors->first('name') }}</div>
                              @endif
                          </div>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
              </form>
          </div>
      </div>
  </div>
