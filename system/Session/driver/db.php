<?php
/**
 * Created by PhpStorm.
 * User: shengjia
 * Date: 2019/4/1
 * Time: 10:56
 */

namespace System\Session\Driver;
class DB implements \System\Session\Driver
{
    /**
     * load a session by ID
     *
     * @param string $id
     * @return array
     */
    public function load($id)
    {
        /**
         * find the session in the database
         */
        $session = $this->query()->find($id);

        /**
         * if the session was found,return it
         */
        if (!is_null($session)) {
            return array(
                'id' => $session->id,
                'last_activity' => $session->last_activity,
                'data' => unserialize($session->data)
            );
        }
    }

    /**
     * save a session
     *
     * @param array $session
     * @return void
     */
    public function save($session)
    {
        /**
         * delete the exiting session row
         */
        $this->delete($session['id']);

        /**
         * insert a new session row
         */
        $this->query()->insert(array(
            'id' => $session['id'],
            'last_activity' => $session['last_activity'],
            'data' => serialize($session['data'])
        ));
    }

    /**
     * delete a session by id
     *
     * @param string $id
     * @return void
     */
    public function delete($id)
    {
        $this->query()->where('id', '=', $id)->delete();
    }

    public function query()
    {
        return \System\DB::table(\System\Config::get('session.table'));
    }
}