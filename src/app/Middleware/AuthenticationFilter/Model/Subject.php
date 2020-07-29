<?php

namespace App\Middleware\AuthenticationFilter;

class Subject
{
    /**
     * 身份标识
     *
     * @var object
     */
    protected $principal;

    /**
     * 凭证
     *
     * @var object
     */
    protected $credentials;

    /**
     * 数据实体，可存放用户名等。
     *
     * @var object
     */
    protected $entity;

    /**
     * 用户权限汇总表
     *
     * @var array
     */
    protected $permissions;

    public function __construct($principal, $credentials, $entity = null)
    {
        
        $this->principal = $principal;
        $this->credentials = $credentials;
        $this->entity = $entity;
        $this->permissions = [];
    }

    /**
     * Get the value of principal
     */
    public function getPrincipal()
    {
        return $this->principal;
    }

    /**
     * Set the value of principal
     *
     * @return self
     */
    public function setPrincipal($principal)
    {
        $this->principal = $principal;

        return $this;
    }

    /**
     * Get the value of entity
     */ 
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * Set the value of entity
     *
     * @return  self
     */ 
    public function setEntity($entity)
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * Get the value of credentials
     */ 
    public function getCredentials()
    {
        return $this->credentials;
    }

    /**
     * Set the value of credentials
     *
     * @return  self
     */ 
    public function setCredentials($credentials)
    {
        $this->credentials = $credentials;

        return $this;
    }

    /**
     * 获取用户权限汇总表
     *
     * @return  array
     */ 
    public function getPermissions()
    {
        return $this->permissions;
    }

    public function addPermission(string $permission){
        $this->permissions[$permission] = true;
        return $this->permissions;
    }

    public function removePermission(string $permission){
        unset($this->permissions[$permission]);
        return $this->permissions;
    }

    public function clearPermissions(){
        $this->permissions = [];
        return $this->permissions;
    }
}
