<div class="modal" tabindex="-1" role="dialog"  class="modal fade" id="addcat" >
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add New Category</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="../../controllers/admin/categoryController.php">

      <div class="modal-body">
        <?php if (isset($_SESSION["success"])): ?>
            <p class="success"><?= $_SESSION["success"]; unset($_SESSION["success"]); ?></p>
        <?php endif; ?>
        <?php if (isset($_SESSION["error"])): ?>
            <p class="error text-danger"><?= $_SESSION["error"]; unset($_SESSION["error"]); ?></p>
        <?php endif; ?>

            <label>Category Name:</label>
            <input type="text" name="category_name" required>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Add Category</button>
        <button type="reset" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
      </div>
      </form>

    </div>
  </div>
</div>