<?php

class PostsController extends BaseController
{
    function onInit()
    {
        $this->authorize();
    }

    function index() 
    {
        $this->posts = $this->model->getAll();
    }

    function create() 
    {
        $this->categories = $this->model->getAllCategories();

        if ($this->isPost){
            $title = $_POST['post_title'];
            if (strlen($title) < 1){
                $this->setValidationError("post_title", "Title too short.");
            }
            $categoryId = $_POST['post_category'];

            $tags = explode(', ', trim($_POST['post_tags']));



            $content = $_POST['post_content'];
            if (strlen($content) < 1){
                $this->setValidationError("post_content", "Post content is empty.");
            }

            $picture_url = "none";
            if ($_FILES['post_picture']['name'] != null) {
                $pictureInfo = $_FILES['post_picture'];
                $type = $pictureInfo['type'];
                if ($type != "image/png" && $type != "image/jpeg" && $type != "image/gif") {
                    $this->addErrorMessage("Error: format now allowed.");
                    $this->redirect("posts");
                }
                $error = $pictureInfo['error'];
                if ($error > 0) {
                    $this->setValidationError("post_picture", "something when wrong with uploading the picture.");
                }
                $size = $pictureInfo['size'];
                if ($size < 0 && $size > 30000) {
                    $this->setValidationError("post_picture", "file size is not in the limit.");
                }
                $tempName = $pictureInfo['tmp_name'];
                $picture_url = uniqid() . "_" . $pictureInfo['name'];
                echo $picture_url;
                move_uploaded_file($tempName,
                    'content/uploads/postPictures/' . $picture_url);
            }
            
            if ($this->formValid()){
                $userId = $_SESSION['user_id'];
                $postId = $this->model->create($title, $content, $userId, $categoryId, $picture_url);
                if ($postId){
                    for($i = 0; $i < count($tags); $i++) {
                        $tag = htmlentities($tags[$i]);
                        $tagExists = $this->model->getTagByName($tag);
                        if (!$tagExists){
                            $tagId = $this->model->insertTag($tag);

                            $this->model->insertTagPost($tagId, $postId);
                        } elseif ($tagExists) {
                            $tagId = $tagExists['id'];
                            $this->model->insertTagPost($tagId, $postId);
                        }

                    }

                    $this->addInfoMessage("Post created.");
                    echo $postId;
                    //$this->redirect("posts");
                }
                else{
                    $this->addErrorMessage("Error: cannot create post.");
                }
            }
        }
    }

    public function delete(int $id)
    {
        if ($this->isPost){
            if ($this->model->delete($id)){
                $this->addInfoMessage("Post deleted.");
            }
            else{
                $this->addErrorMessage("Error: cannot delete post.");
            }
            $this->redirect("posts");
        }
        else{
            $post = $this->model->getById($id);
            if (!$post){
                $this->addErrorMessage("Post does not exist.");
                $this->redirect("posts");
            }
            $this->post = $post;
        }
    }

    public function edit(int $id)
    {

        $this->categories = $this->model->getAllCategories();

        if ($this->isPost){
            $title = $_POST['post_title'];
            if (strlen($title) < 1){
                $this->setValidationError("post_title", "Title too short.");
            }

            $content = $_POST['post_content'];
            if (strlen($content) < 1){
                $this->setValidationError("post_content", "Post content is empty.");
            }

            $date = $_POST['post_date'];

            $dateRegex = '/^\d{2,4}-\d{1,2}-\d{1,2}( \d{1,2}:\d{1,2}(:\d{1,2})?)?$/';
            if (!preg_match($dateRegex, $date)){
                $this->setValidationError("post_date", "Invalid date!");
            }

            $user_id = $_POST['post_user_id'];
            $category_id = $_POST['post_category'];

            if ($user_id <= 0 || $user_id > 1000000){
                $this->setValidationError('user_id', "Invalid author ID.");
            }

            $new_picture_url = $this->model->getById($id)['picture_url'];
            if(isset($_FILES)) {
                if ($_FILES['new_post_picture']['name'] != null) {
                    $pictureInfo = $_FILES['new_post_picture'];
                    $type = $pictureInfo['type'];
                    if ($type != "image/png" && $type != "image/jpeg" && $type != "image/gif") {
                        $this->addErrorMessage("Error: format now allowed.");
                        $this->redirect("posts");
                    }
                    $error = $pictureInfo['error'];
                    if ($error > 0) {
                        $this->setValidationError("new_post_picture", "something when wrong with uploading the picture.");
                    }
                    $size = $pictureInfo['size'];
                    if ($size < 0 && $size > 30000) {
                        $this->setValidationError("new_post_picture", "file size is not in the limit.");
                    }
                    $tempName = $pictureInfo['tmp_name'];
                    $new_picture_url = uniqid() . "_" . $pictureInfo['name'];
                    move_uploaded_file($tempName,
                        'content/uploads/postPictures/' . $new_picture_url);
                }
            }

            if ($this->formValid()){
                if ($this->model->edit($id, $title, $content, $date, $user_id, $category_id, $new_picture_url)){
                    $this->addInfoMessage("Post edited.");
                    $this->redirect("posts");
                }
                else{
                    $this->addErrorMessage("Error: cannot edit post.");
                }
                $this->redirect('posts');
            }

        }
        $post = $this->model->getById($id);
        if (!$post){
            $this->addErrorMessage("Post does not exist.");
            $this->redirect("posts");
        }
        $this->post = $post;
    }
}
