<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {

    public function index(){
        $Response = new \Home\Model\ResponseEntity();
        //if (IS_GET) {
            $keyword = I('get.keyword', '', 'trim');
            $class = I('get.class', '', 'trim');
            $pageIndex = I('get.p', '', 'trim');
            $pageSize = I('get.pageSize', '', 'trim');
            if($class == "") {
                $class = "";
            }
            if ($pageSize == "") {
                $pageSize = 14;
            }
            if ($pageIndex == "") {
                $pageIndex = 0;
            }
            if (true) {
                $Tender = D("Tender");
                $Tender->tender($Response, $class, $pageIndex, $pageSize, $keyword);
            } else {
                // 参数错误
                $Response->setResponseEntity(200, false, "请求参数不能为空", null);
            }
        //} else {
            // 请求方法错误
          //  $Response->setResponseEntity(200, false, "请求方法错误", null);
        //}

        $this->assign("response", $Response);
        $this->assign('list', $Response->data);
        $Page = new \Think\Page($Response->count, $pageSize);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        $this->assign('page', $show);
        $this->display("/index");
    }

    public function toBid() {
        $Response = new \Home\Model\ResponseEntity();
        $Tender = D("Tender");
        $Tender->toBid($Response);
        $this->assign("provinces", $Response->data["provinces"]);
        $this->assign("cities", $Response->data["cities"]);
        $this->display("/bid");
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

    public function bid() {
        $Response = new \Home\Model\ResponseEntity();
        if(IS_POST) {
            $projectname = I('post.projectname', '', 'trim');
            $province = I('post.province', '', 'trim');
            $city = I('post.city', '', 'trim');

            $category1 = I('post.category1', '', 'trim');
            $category2 = I('post.category2', '', 'trim');
            $category3 = I('post.category3', '', 'trim');
            $category4 = I('post.category4', '', 'trim');
            $category5 = I('post.category5', '', 'trim');
            $category6 = I('post.category6', '', 'trim');
            $category7 = I('post.category7', '', 'trim');
            $category8 = I('post.category8', '', 'trim');
            $category9 = I('post.category9', '', 'trim');
            $category10 = I('post.category10', '', 'trim');
            $category11 = I('post.category11', '', 'trim');
            $category12 = I('post.category12', '', 'trim');

            $enddate = I('post.enddate', '', 'trim');
            $registeredcapital = I('post.registeredcapital', '', 'trim');
            $servicearea = I('post.servicearea', '', 'trim');
            $servicetype = I('post.servicetype', '', 'trim');
            $projectcount = I('post.projectcount', '', 'trim');
            $areacount = I('post.areacount', '', 'trim');
            $otherconditions = I('post.otherconditions', '', 'trim');

            $needimg1 = I('post.needimg1', '', 'trim');
            $needimg2 = I('post.needimg2', '', 'trim');
            $needimg3 = I('post.needimg3', '', 'trim');
            $needimg4 = I('post.needimg4', '', 'trim');
            $needimg5 = I('post.needimg5', '', 'trim');
            $needimg6 = I('post.needimg6', '', 'trim');
            $needimg7 = I('post.needimg7', '', 'trim');
            $needimg8 = I('post.needimg8', '', 'trim');

            if($projectname != "" && $province != "" && $city != "" && $enddate != ""
                    && $registeredcapital != "" && $servicearea != ""
                    && $servicetype != "" && $projectcount != "") {
                if($category1 == "" && $category2 == "" && $category3 == ""
                    && $category4 == "" && $category5 == "" && $category6 == "" &&
                    $category7 == "" && $category8 == "" && $category9 == "" &&
                    $category10 == "" && $category11 == "" && $category12 == "") {
                    $Response->setResponseEntity(200, false, "请选择类型", null);
                } else {
                    $rootPath = "./Uploads/attachments/";
                    $upload = $this->uploadFile($rootPath);
                    $info = $upload->upload();
                    if(!$info) {
                        // 上传错误提示错误信息
                        if("没有文件被上传！" == $upload->getError()
                            || "没有上传的文件！" == $upload->getError()) {
                            $Tender = D("Tender");
                            $Tender->bid($Response, $info, $rootPath);
                        } else {
                            $Response->setResponseEntity(200, false, $upload->getError(), null);
                        }
                    }else{
                        // 上传成功
                        $Tender = D("Tender");
                        $Tender->bid($Response, $info, $rootPath);
                    }
                }
            } else {
                $Response->setResponseEntity(200, false, "请求参数不能为空", null);
            }
        } else {
            // 请求方法错误
            $Response->setResponseEntity(200, false, "请求方法错误", null);
        }
        // 返回JSON
        if($Response->isSuccess) {
            $this->index();
        } else {
            $response = new \Home\Model\ResponseEntity();
            $Tender = D("Tender");
            $Tender->toBid($response);
            $this->assign("provinces", $response->data["provinces"]);
            $this->assign("cities", $response->data["cities"]);
            $this->assign("msg", $Response->msg);
            $this->display("/bid");
        }
    }

    public function toBidDetail() {
        $Response = new \Home\Model\ResponseEntity();
        $tenderId = I('get.id', '', 'trim');
        $Tender = D("Tender");
        $Tender->tenderDetail($Response, $tenderId);
        $this->assign("userDetail", $Response->data["userDetail"]);
        $this->assign("detail", $Response->data["detail"]);
        $this->assign("files", $Response->data["files"]);
        $this->display("/bidDetail");
    }

    public function toMyBidDetail() {
        $Response = new \Home\Model\ResponseEntity();
        $tenderId = I('get.id', '', 'trim');
        $Tender = D("Tender");
        $Tender->myTenderDetail($Response, $tenderId);
        $this->assign("userDetail", $Response->data["userDetail"]);
        $this->assign("detail", $Response->data["detail"]);
        $this->assign("files", $Response->data["files"]);
        $this->assign("users", $Response->data["users"]);
        $this->display("/myBidDetail");
    }

    public function toAdminBidDetail() {
        $Response = new \Home\Model\ResponseEntity();
        $tenderId = I('get.id', '', 'trim');
        $Tender = D("Tender");
        $Tender->adminTenderDetail($Response, $tenderId);
        $this->assign("userDetail", $Response->data["userDetail"]);
        $this->assign("detail", $Response->data["detail"]);
        $this->assign("files", $Response->data["files"]);
        $this->assign("users", $Response->data["users"]);
        $this->display("/adminBidDetail");
    }

    public function submit() {
        $Response = new \Home\Model\ResponseEntity();
        if($_SESSION["username"]) {
            if(IS_POST) {
                $tenderId = I('post.tenderId', '', 'trim');
                if($tenderId != "") {
                    $Tender = D("Tender");
                    $Tender->submit($Response, $tenderId);
                } else {
                    $Response->setResponseEntity(200, false, "请求参数不能为空", null);
                }
            } else {
                // 请求方法错误
                $Response->setResponseEntity(200, false, "请求方法错误", null);
            }
        } else {
            $Response->setResponseEntity(200, false, "请先登录", null);
        }
        // 返回JSON
        $this->ajaxReturn($Response, 'JSON');
    }

    public function release() {
        $Response = new \Home\Model\ResponseEntity();
        if($_SESSION["username"]) {
            if(IS_POST) {
                $tenderId = I('post.tenderId', '', 'trim');
                $userId = I('post.userId', '', 'trim');
                if($tenderId != "" && $userId != "") {
                    $Tender = D("Tender");
                    $Tender->release($Response, $tenderId, $userId);
                } else {
                    $Response->setResponseEntity(200, false, "请求参数不能为空", null);
                }
            } else {
                // 请求方法错误
                $Response->setResponseEntity(200, false, "请求方法错误", null);
            }
        } else {
            $Response->setResponseEntity(200, false, "请先登录", null);
        }
        // 返回JSON
        $this->ajaxReturn($Response, 'JSON');
    }

    public function sendBid() {

    }

    public function adminTenders() {
        $Response = new \Home\Model\ResponseEntity();
        if (IS_GET) {
            $keyword = I('get.keyword', '', 'trim');
            $class = I('get.class', '', 'trim');
            $pageIndex = I('get.p', '', 'trim');
            $pageSize = I('get.pageSize', '', 'trim');
            if($class == "") {
                $class = "";
            }
            if ($pageSize == "") {
                $pageSize = 14;
            }
            if ($pageIndex == "") {
                $pageIndex = 0;
            }

            $username = $_SESSION["username"];
            if ($username) {
                $Tender = D("Tender");
                $Tender->adminTenders($Response, $class, $pageIndex, $pageSize, $keyword);
            } else {
                // 参数错误
                $Response->setResponseEntity(200, false, "请先登录", null);
            }
        } else {
            //请求方法错误
            $Response->setResponseEntity(200, false, "请求方法错误", null);
        }

        $this->assign("response", $Response);
        $this->assign('list', $Response->data);
        $Page = new \Think\Page($Response->count, $pageSize);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        $this->assign('page', $show);
        $this->display("/adminTenders");
    }

    public function myTenders() {
        $Response = new \Home\Model\ResponseEntity();
        if (IS_GET) {
            $keyword = I('get.keyword', '', 'trim');
            $class = I('get.class', '', 'trim');
            $pageIndex = I('get.p', '', 'trim');
            $pageSize = I('get.pageSize', '', 'trim');
            if($class == "") {
                $class = "";
            }
            if ($pageSize == "") {
                $pageSize = 14;
            }
            if ($pageIndex == "") {
                $pageIndex = 0;
            }

            $username = $_SESSION["username"];
            if ($username) {
                $Tender = D("Tender");
                $Tender->myTenders($Response, $class, $pageIndex, $pageSize, $keyword);
            } else {
                // 参数错误
                $Response->setResponseEntity(200, false, "请先登录", null);
            }
        } else {
          //请求方法错误
          $Response->setResponseEntity(200, false, "请求方法错误", null);
        }

        $this->assign("response", $Response);
        $this->assign('list', $Response->data);
        $Page = new \Think\Page($Response->count, $pageSize);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        $this->assign('page', $show);
        $this->display("/tenders");
    }

    public function mySignTenders() {
        $Response = new \Home\Model\ResponseEntity();
        if (IS_GET) {
            $keyword = I('get.keyword', '', 'trim');
            $class = I('get.class', '', 'trim');
            $pageIndex = I('get.p', '', 'trim');
            $pageSize = I('get.pageSize', '', 'trim');
            if($class == "") {
                $class = "";
            }
            if ($pageSize == "") {
                $pageSize = 14;
            }
            if ($pageIndex == "") {
                $pageIndex = 0;
            }

            $username = $_SESSION["username"];
            if ($username) {
                $Tender = D("Tender");
                $Tender->mySignTenders($Response, $class, $pageIndex, $pageSize, $keyword);
            } else {
                // 参数错误
                $Response->setResponseEntity(200, false, "请先登录", null);
            }
        } else {
            //请求方法错误
            $Response->setResponseEntity(200, false, "请求方法错误", null);
        }

        $this->assign("response", $Response);
        $this->assign('list', $Response->data);
        $Page = new \Think\Page($Response->count, $pageSize);// 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show();
        $this->assign('page', $show);
        $this->display("/signTenders");
    }
}