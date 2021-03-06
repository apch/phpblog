<?php $this->title = 'Edit post'; ?>

<h1><?=htmlspecialchars($this->title)?></h1>

<main>
    <form method="post" enctype="multipart/form-data">

        <div>Title:</div>
        <input type="text" name="post_title"
               value="<?=htmlspecialchars($this->post['title'])?>">
        <div>Choose Category:</div>
        <select name="post_category">
            <?php foreach ($this->categories as $category) : ?>
                <option value="<?= htmlspecialchars($category['id'])?>" <?php if ($category['id'] == $this->post['category_id']) { ?>selected<?php } ?>><?= htmlspecialchars($category['category'])?></option>
            <?php endforeach; ?>
        </select>
        <div>&nbsp;</div>
        <div>Content:</div>
        <textarea rows="10" name="post_content"
        ><?=htmlspecialchars($this->post['content'])?></textarea>
        <?php if($this->post['picture_url'] != "none" && $this->post['picture_url'] != null): ?>
            <p>Uploaded picture: </p>
            <img class="editPostPicture" src="<?=POSTED_PICTURES?><?=$this->post['picture_url']?>">
        <?php endif; ?>
        <div>
            <label for="post_picture"><strong>Edit picture: </strong></label>
            <input id="post_picture" type="file" name="new_post_picture">
        </div>
        <div>Date (yyyy-MM-dd hh:mm:ss):</div>
        <input type="datetime" name="post_date"
               value="<?=htmlspecialchars($this->post['date'])?>">
        <div>Author ID:</div>
        <input type="text" name="post_user_id"
               value="<?=htmlspecialchars($this->post['user_id'])?>">
        <div><input type="submit" value="Edit post">
            <a href="<?=APP_ROOT?>/posts">[Cancel]</a></div>
    </form>
</main>