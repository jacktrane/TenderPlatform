<?php
/**
 * Created by PhpStorm.
 * User: maozi
 * Date: 2015/11/22
 * Time: 18:42
 */
namespace Home\Model;
use Think\Model;

class UserModel extends Model {

    /**
     * 注册
     * @param $Response
     * @param $username
     * @param $password
     */
    public function register($Response, $username, $password, $info, $rootPath) {
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

        $userResult = $this->getUser($username, $password);
        if($userResult) {
            $Response->setResponseEntity(200, false, "用户已经存在".$registerType, null);
        } else {
            $this->startTrans();//开启事务
            $data['username'] = $username;
            $data['password'] = $password;
            $data['expiretime'] = date("Y-m-d H:i:s", strtotime("+1 Months"));
            $data['token'] = md5($username.$password.$data['expiretime']);
            $data['state'] = 1;
            $newResult = $this->add($data);

            if($newResult) {
                if($info) {
                    // 保存附件
                    $Attachment = D("UserAttachment");
                    $File = D("File");
                    foreach($info as $file) {
                        $aData["savepath"] = $rootPath;
                        $aData["name"] = $file["name"];  // 文件原始名称
                        $aData["savename"] = $file["savename"]; // 文件保存名称
                        $aData["size"] = $file["size"];  // 大小
                        $aData["ext"] = $file["ext"]; // 后缀
                        $aData["md5"] = $file["md5"];  // mime类型
                        $aData["sha1"] = $file["sha1"]; // 后缀
                        $fileResult = $File->add($aData);
                        if($fileResult) {
                            $uaData["user_id"] = $newResult;
                            $uaData["file_id"] = $fileResult;
                            $uaResult = $Attachment->add($uaData);
                            if($uaResult) {

                            } else {
                                $this->rollback();//事务有错回滚
                                $Response->setResponseEntity(200, false, "注册失败", null);
                                return;
                            }
                        } else {
                            $this->rollback();//事务有错回滚
                            $Response->setResponseEntity(200, false, "注册失败", null);
                            return;
                        }
                    }
                }

                // 插入详细资料
                $UserDetail = M("UserDetail");
                $detailData["userid"] = $newResult;
                $detailData["type"] = $registerType;
                $detailData["company"] = $company;
                $detailData["business"] = $business;
                $detailData["telephone"] = $telephone;
                $detailData["email"] = $email;
                $detailData["img1"] = $img1;
                $detailData["img2"] = $img2;
                $detailData["img3"] = $img3;
                $detailData["img4"] = $img4;
                $detailData["img5"] = $img5;
                $detailData["img6"] = $img6;
                $newDetal = $UserDetail->add($detailData);
                if($newDetal) {
                    $this->commit();//提交事务成功
                    $Response->setResponseEntity(200, true, "注册成功", $data);
                } else {
                    $this->rollback();//事务有错回滚
                    $Response->setResponseEntity(200, false, "注册失败", null);
                }
            } else {
                $Response->setResponseEntity(200, false, "注册失败", null);
            }
        }
    }

    public function login($Response, $username, $password) {
        $userResult = $this->getUser($username, $password);
        if ($userResult) {
            // if 用户存在，秘密正确 do 生成token，过期时间返回
            $data['expiretime'] = date("Y-m-d H:i:s", strtotime("+1 Months"));
            $data['token'] = md5($username.$password.$data['expiretime']);

            if ($this->where("username='$username'")->save($data)) {
                $condition['username'] = $username;
                $condition['password'] = $password;
                $condition['_logic'] = 'AND';
                $userResult = $this->where($condition)->find();
                $Response->setResponseEntity(200, true, "登录成功", $userResult);
            } else {
                // 更新数据库出错
                $Response->setResponseEntity(200, false, "更新数据出错", null);
            }
        } else {
            // else 返回错误信息
            $Response->setResponseEntity(200, false, "用户名与密码不匹配", null);
        }
    }

    public function getMyProfile($Response) {
        $username = $_SESSION["username"];
        $userResult = $this->where("username = '$username'")->find();
        if($userResult) {
            $UserDetail = M("UserDetail");
            $userId = $userResult["id"];
            $detailResult = $UserDetail->where("userid = '$userId'")->find();

            $UserAttachment = M("UserAttachment");
            $fileResult = $UserAttachment->where("user_id = '$userId'")->select();

            $File = D("File");
            for($i = 0; $i < count($fileResult); $i ++) {
                $fileId = $fileResult[$i]["file_id"];
                $files[$i] = $File->where("id = '$fileId'")->find();
            }
            $responseData["user"] = $userResult;
            $responseData["detail"] = $detailResult;
            $responseData["files"] = $files;
            $Response->setResponseEntity(200, true, "查询成功", $responseData);
        } else {
            $Response->setResponseEntity(200, false, "查询失败", null);
        }
    }

    public function getProfile($Response, $userId) {
        $userResult = $this->where("id = '$userId'")->find();
        if($userResult) {
            $UserDetail = M("UserDetail");
            $userId = $userResult["id"];
            $detailResult = $UserDetail->where("userid = '$userId'")->find();

            $UserAttachment = M("UserAttachment");
            $fileResult = $UserAttachment->where("user_id = '$userId'")->select();

            $File = D("File");
            for($i = 0; $i < count($fileResult); $i ++) {
                $fileId = $fileResult[$i]["file_id"];
                $files[$i] = $File->where("id = '$fileId'")->find();
            }
            $responseData["user"] = $userResult;
            $responseData["detail"] = $detailResult;
            $responseData["files"] = $files;
            $Response->setResponseEntity(200, true, "查询成功", $responseData);
        } else {
            $Response->setResponseEntity(200, false, "查询失败", null);
        }
    }

    public function logout($Response, $username, $password) {
        $userResult = $this->getUser($username, $password);
        if ($userResult) {
            $data['expiretime'] = date("Y-m-d H:i:s");
            $data['token'] = "";
            $condition['username'] = $username;
            $condition['password'] = $password;
            $condition['_logic'] = 'AND';
            if ($this->where($condition)->save($data)) {
                $Response->setResponseEntity(200, true, "注销成功", null);
            } else {
                // 更新数据库出错
                $Response->setResponseEntity(200, false, "更新数据出错", null);
            }
        } else {
            // else 返回错误信息
            $Response->setResponseEntity(200, false, "用户不存在", null);
        }
    }

    public function changePassword($Response, $username, $password, $newPassword) {
        $userResult = $this->getUser($username, $password);
        if ($userResult) {
            $data['expiretime'] = date("Y-m-d H:i:s", strtotime("+1 Months"));
            $data['token'] = md5($username.$newPassword.$data['expiretime']);
            $data['password'] = $newPassword;

            $condition['username'] = $username;
            $condition['password'] = $password;
            $condition['_logic'] = 'AND';
            if ($this->where($condition)->save($data)) {
                $condition['password'] = $newPassword;
                $userResult = $this->where($condition)->find();
                $Response->setResponseEntity(200, true, "密码修改成功", $userResult);
            } else {
                // 更新数据库出错
                $Response->setResponseEntity(200, false, "密码修改失败", null);
            }
        } else {
            // else 返回错误信息
            $Response->setResponseEntity(200, false, "用户不存在", null);
        }
    }

    public function updateProfile($Response) {

    }

    /**
     * 获取用户
     * @param $username
     * @param $password
     * @return mixed|null
     */
    private function getUser($username, $password) {
        if($username != "" && $password != "") {
            $condition['username'] = $username;
            $condition['password'] = $password;
            $condition['_logic'] = 'AND';
            $userResult = $this->where($condition)->find();
            return $userResult;
        } else {
            return null;
        }
    }
}
