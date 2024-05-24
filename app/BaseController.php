<?php
declare (strict_types = 1);

namespace app;

use think\App;
use think\exception\ValidateException;
use think\Validate;
/**
 * 控制器基础类
 */
abstract class BaseController
{
    public $verifyObj; //验证类
    public $verifyData; // 验证成功后的数据

    public $helperObj = array(); // helper类

    public function response(array $data = [] , array $list = [] , string $code = '200')
    {
        $data = [
            'status' => true,
            'error_msg' => 'OK',
            'error_code' => '',
            'data' => empty($data) ? null : $data,
            'list' => $list,
         ];
         !config('app.debug') ? false : $data['debug'] = array(
            'run_time' => get_run_time() . ' ms'
        );

        return response()->create($data, 'json' ,(int)$code);
    }
    /**
     * Request实例
     * @var \think\Request
     */
    protected $request;

    /**
     * 应用实例
     * @var \think\App
     */
    protected $app;

    /**
     * 是否批量验证
     * @var bool
     */
    protected $batchValidate = false;

    /**
     * 控制器中间件
     * @var array
     */
    protected $middleware = [];

    /**
     * 构造方法
     * @access public
     * @param  App  $app  应用对象
     */
    public function __construct(App $app)
    {
        $this->app     = $app;
        $this->request = $this->app->request;

        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {}

    /**
     * 验证数据
     * @access protected
     * @param  array        $data     数据
     * @param  string|array $validate 验证器名或者验证规则数组
     * @param  array        $message  提示信息
     * @param  bool         $batch    是否批量验证
     * @return array|string|true
     * @throws ValidateException
     */
    protected function validate(array $data, $validate, array $message = [], bool $batch = false)
    {
        if (is_array($validate)) {
            $v = new Validate();
            $v->rule($validate);
        } else {
            if (strpos($validate, '.')) {
                // 支持场景
                [$validate, $scene] = explode('.', $validate);
            }
            $class = false !== strpos($validate, '\\') ? $validate : $this->app->parseClass('validate', $validate);
            $v     = new $class();
            if (!empty($scene)) {
                $v->scene($scene);
            }
        }

        $v->message($message);

        // 是否批量验证
        if ($batch || $this->batchValidate) {
            $v->batch(true);
        }

        return $v->failException(true)->check($data);
    }
     /**
     * 验证
     * @param \Illuminate\Http\Request $request
     * @throws ApiException
     */
    public function verify(array $rule, $data = 'GET')
    {
        if (empty($this->verifyObj)) {
            return $this->verifyObj = new \app\Common\Verify();

        }
        $result = $this->verifyObj->check($rule, $data);
        $this->verifyData = $this->verifyObj->getData();

        return $result;
    }

}
