<?php
/**
 * Created by PhpStorm.
 * User: maozi
 * Date: 2015/11/22
 * Time: 19:41
 */
namespace Home\Controller;
use Think\Controller;

class UserController extends Controller {

    public function toRegister() {
        $this->display("/register");
    }

    /**
     * @param $rootPath
     * @return \Think\Upload
     */
    private function uploadFile($rootPath) {
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize = 1024 * 1024 * 1024 * 100;// 设置附件上传大小 100M
//        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     $rootPath; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        $upload->autoSub = false;
        // 文件使用time函数命名
//        $upload->saveName = 'time';
        return $upload;
    }

    /**
     * 注册
     */
    public function register() {
        $Response = new \Home\Model\ResponseEntity();
        if(IS_POST) {
            $registerType = I('post.registerType', '', 'trim');
            $username = I('post.username', '', 'trim');
            $company = I('post.company', '', 'trim');
            $business = I('post.business', '', 'trim');
            $telephone = I('post.telephone', '', 'trim');
            $email = I('post.email', '', 'trim');
            $password = I('post.password', '', 'trim');
            $confirmPassword = I('post.confirmPassword', '', 'trim');
            $img1 = I('post.img1', '', 'trim');
            $img2 = I('post.img2', '', 'trim');
            $img3 = I('post.img3', '', 'trim');
            $img4 = I('post.img4', '', 'trim');
            $img5 = I('post.img5', '', 'trim');
            $img6 = I('post.img6', '', 'trim');

            if($registerType != "" && $username != "" && $company != ""
                && $business != "" && $telephone !="" && $email != ""
                && $password != "" && $confirmPassword != "") {
            // if (!empty($registerType)
            //     &&!empty($username)
            //     &&!empty($company)
            //     &&!empty($business)
            //     &&!empty($telephone)
            //     &&!empty($email)
            //     &&!empty($password)
            //     &&!empty($confirmPassword)) {
                if($password != $confirmPassword) {
                    $Response->setResponseEntity(200, false, "密码不一致", null);
                } else {
                    $rootPath = "./Uploads/attachments/";
                    $upload = $this->uploadFile($rootPath);
                    $info = $upload->upload();
                    if(!$info) {
                        // 上传错误提示错误信息
                        if("没有文件被上传！" == $upload->getError() || "没有上传的文件！" == $upload->getError()) {
                            $User = D("User");
                            $User->register($Response, $username, $password, $info, $rootPath);
                        } else {
                            $Response->setResponseEntity(200, false, $upload->getError(), null);
                        }
                    }else{
                        // 上传成功
                        $User = D("User");
                        $User->register($Response, $username, $password, $info, $rootPath);
                    }
                }
            } else {
                $Response->setResponseEntity(200, false, "请求参数不能为空", null);
            }
        } else {
            // 请求方法错误
            $Response->setResponseEntity(200, false, "请求方法错误", null);
        }

        // 保存Session，缓存Token
        if ($Response->code == 200 && $Response->isSuccess) {
            $this->saveOAuth($Response->data);
        }
        // 返回JSON
//        $this->ajaxReturn($Response, 'JSON');
        if($Response->isSuccess) {
            $this->display("/index");
        } else {
            $this->assign("msg", $Response->msg);
            $this->display("/register");
        }
    }

    /**
     * 保存Session，缓存Token
     * @param $user
     * @return bool
     */
    private function saveOAuth($user) {
        $username = $user['username'];
        $password = $user['password'];
        $token = $user['token'];
        if($username != "" && $password != "" && $token != "") {
            // 保存session
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['token'] = $token;

            // TODO 缓存登录凭证

            return true;
        } else {
            return false;
        }
    }

    /**
     * 清除Session，Token
     * @param $user
     * @return bool
     */
    private function clearOAuth($user) {
        $username = $user['username'];
        $password = $user['password'];
        $token = $user['token'];
        if($username != "" && $password != "" && $token != "") {
            // 保存session
            unset($_SESSION['username']);
            unset($_SESSION['password']);
            unset($_SESSION['token']);

            // TODO 删除缓存登录凭证
            return true;
        } else {
            return false;
        }
    }

    public function toLogin() {
        $this->display("/login");
    }

    /**
     * 登录
     */
    public function login() {
        $Response = new \Home\Model\ResponseEntity();
        if(IS_POST) {
            $username = I('post.username', '', 'trim');
            $password = I('post.password', '', 'trim');

            if($username != "" && $password != "") {
                $User = D("User");
                $User->login($Response, $username, $password);
            } else {
                $Response->setResponseEntity(200, false, "请求参数不能为空", null);
            }
        } else {
            // 请求方法错误
            $Response->setResponseEntity(200, false, "请求方法错误", null);
        }
        // 保存Session，缓存Token
        if ($Response->code == 200 && $Response->isSuccess) {
            $this->saveOAuth($Response->data);
        }
        // 返回JSON
        $this->ajaxReturn($Response, 'JSON');
    }

    /**
     * 注销
     */
    public function logout() {
        $Response = new \Home\Model\ResponseEntity();
        if(IS_POST) {
            $username = I('post.username', '', 'trim');
            $password = I('post.password', '', 'trim');

            if($username != "" && $password != "") {
                $User = D("User");
                $User->logout($Response, $username, $password);
            } else {
                $Response->setResponseEntity(200, false, "请求参数不能为空", null);
            }
        } else {
            // 请求方法错误
            $Response->setResponseEntity(200, false, "请求方法错误", null);
        }
        // 删除Session，删除缓存Token
        if ($Response->code == 200 && $Response->isSuccess) {
            $this->clearOAuth($Response->data);
        }
        // 返回JSON
        $this->ajaxReturn($Response, 'JSON');
    }

    /**
     * 更改密码
     */
    public function changePassword() {
        $Response = new \Home\Model\ResponseEntity();
        if(IS_POST) {
            $username = I('post.username', '', 'trim');
            $password = I('post.password', '', 'trim');
            $newPassword = I('post.newPassword', '', 'trim');

            if($username != "" && $password != "" && $newPassword != "") {
                $User = D("User");
                $User->changePassword($Response, $username, $password, $newPassword);
            } else {
                $Response->setResponseEntity(200, false, "请求参数不能为空", null);
            }
        } else {
            // 请求方法错误
            $Response->setResponseEntity(200, false, "请求方法错误", null);
        }
        // 返回JSON
        $this->ajaxReturn($Response, 'JSON');
    }

    public function toMyProfile() {
        $Response = new \Home\Model\ResponseEntity();
        $User = D("User");
        $User->getMyProfile($Response);
        $this->assign("user", $Response->data["user"]);
        $this->assign("detail", $Response->data["detail"]);
        $this->assign("files", $Response->data["files"]);
        $this->display("/myProfile");
    }

    public function toProfile() {
        $Response = new \Home\Model\ResponseEntity();
        $userId = I('get.userid', '', 'trim');
        $User = D("User");
        $User->getProfile($Response, $userId);
        $this->assign("user", $Response->data["user"]);
        $this->assign("detail", $Response->data["detail"]);
        $this->assign("files", $Response->data["files"]);
        $this->display("/profile");
    }

    /**
     * 修改个人资料
     */
    public function updateProfile() {
        $Response = new \Home\Model\ResponseEntity();
        if(IS_POST) {

        } else {
            // 请求方法错误
            $Response->setResponseEntity(200, false, "请求方法错误", null);
        }
        // 返回JSON
        $this->ajaxReturn($Response, 'JSON');
    }

    /**
     * 发布招投标表 页面
     */
    public function tobid() {
        $this -> display("/bid");
    }

    /**
     * 招投标表 表单
     */
    public function bid() {
        
        $Response = new \Home\Model\TenderModel();
        if(IS_POST) {
            $registerType = I('post.registerType', '', 'trim');
            $username = I('post.username', '', 'trim');
            $company = I('post.company', '', 'trim');
            $business = I('post.business', '', 'trim');
            $telephone = I('post.telephone', '', 'trim');
            $email = I('post.email', '', 'trim');
            $password = I('post.password', '', 'trim');
            $confirmPassword = I('post.confirmPassword', '', 'trim');
            $img1 = I('post.img1', '', 'trim');
            $img2 = I('post.img2', '', 'trim');
            $img3 = I('post.img3', '', 'trim');
            $img4 = I('post.img4', '', 'trim');
            $img5 = I('post.img5', '', 'trim');
            $img6 = I('post.img6', '', 'trim');

            if($registerType != "" && $username != "" && $company != ""
                && $business != "" && $telephone !="" && $email != ""
                && $password != "" && $confirmPassword != "") {
            // if (!empty($registerType)
            //     &&!empty($username)
            //     &&!empty($company)
            //     &&!empty($business)
            //     &&!empty($telephone)
            //     &&!empty($email)
            //     &&!empty($password)
            //     &&!empty($confirmPassword)) {
                if($password != $confirmPassword) {
                    $Response->setResponseEntity(200, false, "密码不一致", null);
                } else {
                    $rootPath = "./Uploads/attachments/";
                    $upload = $this->uploadFile($rootPath);
                    $info = $upload->upload();
                    if(!$info) {
                        // 上传错误提示错误信息
                        if("没有文件被上传！" == $upload->getError() || "没有上传的文件！" == $upload->getError()) {
                            $User = D("User");
                            $User->register($Response, $username, $password, $info, $rootPath);
                        } else {
                            $Response->setResponseEntity(200, false, $upload->getError(), null);
                        }
                    }else{
                        // 上传成功
                        $User = D("User");
                        $User->register($Response, $username, $password, $info, $rootPath);
                    }
                }
            } else {
                $Response->setResponseEntity(200, false, "请求参数不能为空", null);
            }
        } else {
            // 请求方法错误
            $Response->setResponseEntity(200, false, "请求方法错误", null);
        }

        // 保存Session，缓存Token
        if ($Response->code == 200 && $Response->isSuccess) {
            $this->saveOAuth($Response->data);
        }
        // 返回JSON
//        $this->ajaxReturn($Response, 'JSON');
        if($Response->isSuccess) {
            $this->display("/index");
        } else {
            $this->assign("msg", $Response->msg);
            $this->display("/register");
        }
    }

}