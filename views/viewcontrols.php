<div class="fv-footer">
    <span class="file-count"><?php echo "$printed item(s)"; ?></span>
    <form class="view-selector" method="POST">
      <select name="size">
        <option value="4em"<?php echo $viewsize=='4em'?' selected':''; ?>>Small</option>
        <option value="6em"<?php echo $viewsize=='6em'?' selected':''; ?>>Medium</option>
        <option value="8em"<?php echo $viewsize=='8em'?' selected':''; ?>>Large</option>
      </select>
      &nbsp;|&nbsp;
      <input type="radio" id="gridview" name="viewmode" value="grid"<?php echo $viewmode=='grid'?' checked':''; ?>>
      <label for="gridview">Grid</label>
      <input type="radio" id="listview" name="viewmode" value="list"<?php echo $viewmode=='list'?' checked':''; ?>>
      <label for="listview">List</label>
      &nbsp;
      <input type="submit" value="💾">
    </form>
  </div>