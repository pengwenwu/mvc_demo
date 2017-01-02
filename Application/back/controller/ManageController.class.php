<?php

class ManageController extends PlatformController
{
    public function IndexAction()
    {
        //登录首页
        include VIEW_PATH . 'index.html';
    }

    /**
     * 引入各框架页面，需要动作来实现
     */
    public function TopAction()
    {
        include VIEW_PATH . 'top.html';
    }

    public function MenuAction()
    {
        include VIEW_PATH . 'menu.html';
    }

    public function DragAction()
    {
        include VIEW_PATH . 'drag.html';
    }

    public function MainAction()
    {
        include VIEW_PATH . 'main.html';
    }
}