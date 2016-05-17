<?php
/**
 * Created by PhpStorm.
 * User: maozi
 * Date: 2015/11/22
 * Time: 18:42
 */
namespace Home\Model;
use Think\Model;

class TenderModel extends Model {

    public function tender($Response, $class, $pageIndex, $pageSize, $keyword) {
       if($class == "") {
           if($keyword == "") {
               $result = $this->page($pageIndex . ",$pageSize")->select();
               $count = $this->count();
           } else {
               $where["projectname"] = array("like", "%$keyword%");
               $result = $this->where($where)->page($pageIndex . ",$pageSize")->select();
               $count = $this->where($where)->count();
           }
        } else {
           $CategoryTender = D("CategoryTender");
           $categoryResult = $CategoryTender->where("category_id = '$class'")->page($pageIndex . ",$pageSize")->select();
           $count = $CategoryTender->where("category_id = '$class'")->count();
           $Tender = D("Tender");
           for($i = 0; $i < count($categoryResult); $i ++) {
               $tenderId = $categoryResult[$i]["tender_id"];
               $result[$i] = $Tender->where("id = '$tenderId'")->find();
           }
        }

        if ($result) {
            $Response->setResponseEntityWithCount(200, true, "查询成功", $result, $count);
        } else if ($result == null) {
            $Response->setResponseEntityWithCount(200, true, "没有数据", null, 0);
        } else {
            $Response->setResponseEntityWithCount(200, false, "查询数据出错", null, 0);
        }
    }

    public function adminTenders($Response, $class, $pageIndex, $pageSize, $keyword) {
        $username = $_SESSION["username"];
        $User = D("User");
        $userResult = $User->where("username = '$username'")->find();
        if($userResult) {
            $this->tender($Response, $class, $pageIndex, $pageSize, $keyword);
        } else {
            $Response->setResponseEntityWithCount(200, false, "非法用户", $userResult, 0);
        }
    }

    public function myTenders($Response, $class, $pageIndex, $pageSize, $keyword) {
        $username = $_SESSION["username"];
        $User = D("User");
        $userResult = $User->where("username = '$username'")->find();
        if($userResult) {
            $userId = $userResult["id"];
            if($keyword == "") {
                $result = $this->where("user_id = '$userId'")->page($pageIndex . ",$pageSize")->select();
                $count = $this->where("user_id = '$userId'")->count();
            } else {
                $result = $this->where("user_id = '$userId' AND projectname like '%$keyword%'")->page($pageIndex . ",$pageSize")->select();
                $count = $this->where("user_id = '$userId' AND projectname like '%$keyword%'")->count();
            }

            if ($result) {
                $Response->setResponseEntityWithCount(200, true, "查询成功", $result, $count);
            } else if ($result == null) {
                $Response->setResponseEntityWithCount(200, true, "没有数据", null, 0);
            } else {
                $Response->setResponseEntityWithCount(200, false, "查询数据出错", null, 0);
            }
        } else {
            $Response->setResponseEntityWithCount(200, false, "非法用户", $userResult, 0);
        }
    }

    public function mySignTenders($Response, $class, $pageIndex, $pageSize, $keyword) {
        $username = $_SESSION["username"];
        $User = D("User");
        $userResult = $User->where("username = '$username'")->find();
        if($userResult) {
            $userId = $userResult["id"];
            $UserTender = D("UserTender");

            $result = $UserTender->where("user_id = '$userId'")->page($pageIndex . ",$pageSize")->select();
            $count = $UserTender->where("user_id = '$userId'")->count();
            $Tender = D("Tender");
            if($keyword == "") {
                for($i = 0; $i < count($result); $i ++) {
                    $tenderId = $result[$i]["id"];
                    $tenders[$i] = $Tender->where("id = '$tenderId'")->find();
                }
            } else {
//                $result = $UserTender->where("user_id = '$userId'")->select();
//                $count = $UserTender->where("user_id = '$userId'")->count();
//                for($i = 0; $i < count($result); $i ++) {
//                    $tenderId = $result[$i]["id"];
//                    $tenders[$i] = $Tender->where("id = '$tenderId' AND projectname like '%$keyword%'")->find();
//                }
            }

            if ($result) {
                $Response->setResponseEntityWithCount(200, true, "查询成功", $tenders, $count);
            } else if ($result == null) {
                $Response->setResponseEntityWithCount(200, true, "没有数据", null, 0);
            } else {
                $Response->setResponseEntityWithCount(200, false, "查询数据出错", null, 0);
            }
        } else {
            $Response->setResponseEntityWithCount(200, false, "非法用户", $userResult, 0);
        }
    }

    public function tenderDetail($Response, $tenderId) {
        $tenderResult = $this->where("id = '$tenderId'")->find();
        if ($tenderResult) {
            $UserDetail = D("UserDetail");
            $userId = $tenderResult["user_id"];
            $userDetail = $UserDetail->where("userid = '$userId'")->find();

            $TenderFile = D("TenderFile");
            $fileResult = $TenderFile->where("tender_id = '$tenderId'")->select();
            if($fileResult || $fileResult == null) {
                $File = D("File");
                for($i = 0; $i < count($fileResult); $i ++) {
                    $fileId = $fileResult[$i]["file_id"];
                    $files[$i] = $File->where("id = '$fileId'")->find();
                }


                $responseData["files"] = $files;
                $responseData["detail"] = $tenderResult;
                $responseData["userDetail"] = $userDetail;
                $Response->setResponseEntity(200, true, "查询数据成功", $responseData);
            } else {
                $Response->setResponseEntity(200, false, "查询数据出错", null);
            }
        } else {
            // else 返回错误信息
            $Response->setResponseEntity(200, false, "不存在此标", null);
        }
    }

    public function myTenderDetail($Response, $tenderId) {
        $tenderResult = $this->where("id = '$tenderId'")->find();
        if ($tenderResult) {
            $UserDetail = D("UserDetail");
            $userId = $tenderResult["user_id"];
            $userDetail = $UserDetail->where("userid = '$userId'")->find();

            $TenderFile = D("TenderFile");
            $fileResult = $TenderFile->where("tender_id = '$tenderId'")->select();
            if($fileResult || $fileResult == null) {
                $File = D("File");
                for($i = 0; $i < count($fileResult); $i ++) {
                    $fileId = $fileResult[$i]["file_id"];
                    $files[$i] = $File->where("id = '$fileId'")->find();
                }

                $UserTender = D("UserTender");
                $utResult = $UserTender->where("tender_id = '$tenderId' AND state = '2'")->select();
                for($j = 0; $j < count($utResult); $j ++) {
                    $id = $utResult["$j"]["user_id"];
                    $users[$j] = $UserDetail->where("userid = '$id'")->find();
                }

                $responseData["users"] = $users;
                $responseData["files"] = $files;
                $responseData["detail"] = $tenderResult;
                $responseData["userDetail"] = $userDetail;
                $Response->setResponseEntity(200, true, "查询数据成功", $responseData);
            } else {
                $Response->setResponseEntity(200, false, "查询数据出错", null);
            }
        } else {
            // else 返回错误信息
            $Response->setResponseEntity(200, false, "不存在此标", null);
        }
    }

    public function adminTenderDetail($Response, $tenderId) {
        $tenderResult = $this->where("id = '$tenderId'")->find();
        if ($tenderResult) {
            $UserDetail = D("UserDetail");
            $userId = $tenderResult["user_id"];
            $userDetail = $UserDetail->where("userid = '$userId'")->find();

            $TenderFile = D("TenderFile");
            $fileResult = $TenderFile->where("tender_id = '$tenderId'")->select();
            if($fileResult || $fileResult == null) {
                $File = D("File");
                for($i = 0; $i < count($fileResult); $i ++) {
                    $fileId = $fileResult[$i]["file_id"];
                    $files[$i] = $File->where("id = '$fileId'")->find();
                }

                $UserTender = D("UserTender");
                $utResult = $UserTender->where("tender_id = '$tenderId' AND state = '1'")->select();
                for($j = 0; $j < count($utResult); $j ++) {
                    $id = $utResult["$j"]["user_id"];
                    $users[$j] = $UserDetail->where("userid = '$id'")->find();
                }

                $responseData["users"] = $users;
                $responseData["files"] = $files;
                $responseData["detail"] = $tenderResult;
                $responseData["userDetail"] = $userDetail;
                $Response->setResponseEntity(200, true, "查询数据成功", $responseData);
            } else {
                $Response->setResponseEntity(200, false, "查询数据出错", null);
            }
        } else {
            // else 返回错误信息
            $Response->setResponseEntity(200, false, "不存在此标", null);
        }
    }

    public function toBid($Response) {
        $Province = M("Province");
        $City = M("City");
        $provinces = $Province->select();
        $cities = $City->select();
        $responseData["provinces"] = $provinces;
        $responseData["cities"] = $cities;
        $Response->setResponseEntity(200, true, "登录成功", $responseData);
    }

    public function bid($Response, $info, $rootPath) {
        $User = D("User");
        $username = $_SESSION["username"];
        $userResult = $User->where("username = '$username'")->find();
        if($userResult) {
            $projectname = I('post.projectname', '', 'trim');
            $province = I('post.province', '', 'trim');
            $city = I('post.city', '', 'trim');

            $category[1] = I('post.category1', '', 'trim');
            $category[2] = I('post.category2', '', 'trim');
            $category[3] = I('post.category3', '', 'trim');
            $category[4] = I('post.category4', '', 'trim');
            $category[5] = I('post.category5', '', 'trim');
            $category[6] = I('post.category6', '', 'trim');
            $category[7] = I('post.category7', '', 'trim');
            $category[8] = I('post.category8', '', 'trim');
            $category[9] = I('post.category9', '', 'trim');
            $category[10] = I('post.category10', '', 'trim');
            $category[11] = I('post.category11', '', 'trim');
            $category[12] = I('post.category12', '', 'trim');

            $enddate = I('post.enddate', '', 'trim');
            $registeredcapital = I('post.registeredcapital', '', 'trim');
            $servicearea = I('post.servicearea', '', 'trim');
            $servicetype = I('post.servicetype', '', 'trim');
            $projectcount = I('post.projectcount', '', 'trim');
            $areacount = I('post.areacount', '', 'trim');
            if($areacount == "on") {
                $areacount = 1;
            } else {
                $areacount = 0;
            }
            $otherconditions = I('post.otherconditions', '', 'trim');

            $needimg1 = I('post.needimg1', '', 'trim');
            if($needimg1 == "on") {
                $needimg1 = 1;
            } else {
                $needimg1 = 0;
            }
            $needimg2 = I('post.needimg2', '', 'trim');
            if($needimg2 == "on") {
                $needimg2 = 1;
            } else {
                $needimg2 = 0;
            }
            $needimg3 = I('post.needimg3', '', 'trim');
            if($needimg3 == "on") {
                $needimg3 = 1;
            } else {
                $needimg3 = 0;
            }
            $needimg4 = I('post.needimg4', '', 'trim');
            if($needimg4 == "on") {
                $needimg4 = 1;
            } else {
                $needimg4 = 0;
            }
            $needimg5 = I('post.needimg5', '', 'trim');
            if($needimg5 == "on") {
                $needimg5 = 1;
            } else {
                $needimg5 = 0;
            }
            $needimg6 = I('post.needimg6', '', 'trim');
            if($needimg6 == "on") {
                $needimg6 = 1;
            } else {
                $needimg6 = 0;
            }
            $needimg7 = I('post.needimg7', '', 'trim');
            if($needimg7 == "on") {
                $needimg7 = 1;
            } else {
                $needimg7 = 0;
            }
            $needimg8 = I('post.needimg8', '', 'trim');
            if($needimg8 == "on") {
                $needimg8 = 1;
            } else {
                $needimg8 = 0;
            }

            $this->startTrans();//开启事务

            $tenderData["user_id"] = $userResult["id"];
            $tenderData["projectname"] = $projectname;
            $tenderData["province"] = $province;
            $tenderData["city"] = $city;
            $tenderData["enddate"] = $enddate;
            $tenderData["registeredcapital"] = $registeredcapital;
            $tenderData["servicearea"] = $servicearea;
            $tenderData["servicetype"] = $servicetype;
            $tenderData["projectcount"] = $projectcount;
            $tenderData["areacount"] = $areacount;
            $tenderData["otherconditions"] = $otherconditions;
            $tenderData["needimg1"] = $needimg1;
            $tenderData["needimg2"] = $needimg2;
            $tenderData["needimg3"] = $needimg3;
            $tenderData["needimg4"] = $needimg4;
            $tenderData["needimg5"] = $needimg5;
            $tenderData["needimg6"] = $needimg6;
            $tenderData["needimg7"] = $needimg7;
            $tenderData["needimg8"] = $needimg8;
            $tenderData["state"] = 1;  // 状态。未审核

            $addResult = $this->add($tenderData);
            if($addResult) {
                if($info) {
                    // 保存附件
                    $TenderFile = D("TenderFile");
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
                            $tfData["tender_id"] = $addResult;
                            $tfData["file_id"] = $fileResult;
                            $tfResult = $TenderFile->add($tfData);
                            if($tfResult) {

                            } else {
                                $this->rollback();//事务有错回滚
                                $Response->setResponseEntity(200, false, "发布失败", null);
                                return;
                            }
                        } else {
                            $this->rollback();//事务有错回滚
                            $Response->setResponseEntity(200, false, "发布失败", null);
                            return;
                        }
                    }
                }

                $CategoryTender = D("CategoryTender");
                for($i = 1; $i <= count($category); $i ++) {
                    if($category[$i] != "") {
                        $ctData["category_id"] = $i;
                        $ctData["tender_id"] = $addResult;
                        $ctResult = $CategoryTender->add($ctData);
                        if($ctResult) {

                        } else {
                            $this->rollback();//事务有错回滚
                            $Response->setResponseEntity(200, false, "发布失败", null);
                            return;
                        }
                    }
                }
                $this->commit();//提交事务成功
                $Response->setResponseEntity(200, true, "提交成功", $addResult);
            } else {
                $Response->setResponseEntity(200, false, "提交失败", $addResult);
            }
        } else {
            $Response->setResponseEntity(200, false, "非法账号", null);
        }
    }

    public function submit($Response, $tenderId) {
        $tenderResult = $this->where("id = '$tenderId'")->find();
        if ($tenderResult) {
            $data["numbers"] = $tenderResult["$tenderResult"] + 1;
            $result = $this->where("id = '$tenderId'")->save($data);
            if($result !== false) {
                $UserTender = D("UserTender");
                // 检查是否已经报名
                $username = $_SESSION["username"];
                $User = D("User");
                $userResult = $User->where("username = '$username'")->find();
                $userId = $userResult["id"];
                $checkResult = $UserTender->where("tender_id = '$tenderId' AND user_id = '$userId'")->find();
                if($checkResult) {
                    $Response->setResponseEntity(200, true, "已经报过名", null);
                } else {
                    $utData["user_id"] = $tenderResult["user_id"];
                    $utData["tender_id"] = $tenderId;
                    $utData["state"] = 1;
                    $utData["selected"] = 2;
                    $UserTender->add($utData);
                    $Response->setResponseEntity(200, true, "报名成功", null);
                }
            }else{
                $Response->setResponseEntity(200, false, "报名失败", null);
            }
        } else {
            // else 返回错误信息
            $Response->setResponseEntity(200, false, "不存在此标", null);
        }
    }

    public function release($Response, $tenderId, $userId) {
        $UserTender = D("UserTender");
        $tenderResult = $UserTender->where("tender_id = '$tenderId'  AND user_id = '$userId'")->find();
        if ($tenderResult) {
            $data["state"] = 2;
            $result = $UserTender->where("tender_id = '$tenderId'  AND user_id = '$userId'")->save($data);
            if($result !== false) {
                $Response->setResponseEntity(200, true, "操作成功", null);
            } else {
                $Response->setResponseEntity(200, false, "操作失败", null);
            }
        } else {
            // else 返回错误信息
            $Response->setResponseEntity(200, false, "不存在此标", null);
        }
    }
}
