<?php
namespace Test;

trait WithMockHelper
{
    /** @var \PHPUnit_Framework_MockObj_MockObj[] */
    private $mocks;
    
    public function __get($class)
    {
        return $this->mocks[$class];
    }
    
    protected function setupMocks(array $classes)
    {
        foreach ($classes as $class) {
            $this->mocks[$class] = $this->getMockBuilder($class)
                    ->disableOriginalConstructor()
                    ->getMock();
        }
    }
    
    /**
     * Set expectations based on mock configuration array.
     * 
     * @param string $class
     * @param array $expectations
     */
    protected function setMockExpectations($class, $expectations)
    {
        foreach ($expectations as $method => $expect) {
            list($times, $params, $return) = $expect;
            $m = $this->mocks[$class]->expects($times)
                    ->method($method);
                    
            if (!is_null($params)) {
                call_user_func_array([$m, 'with'], $params);
            }
            
            $m->willReturn($return);
        }
        
        $ref = new \ReflectionClass($class);
        $methods = $ref->getMethods(\ReflectionMethod::IS_PUBLIC);
        
        foreach ($methods as $m) {
            if (!in_array($m->getName(), array_keys($expectations)) && 
                    $m->getName() != '__construct') {
                
                $this->mocks[$class]->expects($this->never())
                        ->method($m->getName());
            }
        }
    }
}
