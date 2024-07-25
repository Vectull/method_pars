<?php
namespace NamePlugin;

class NameApi {
    public $api_url;

    public function list_vacancies($post, $vid = 0) {
        global $wpdb;

        if (!is_object($post)) {
            return false;
        }

        $page = 0;
        $found = false;
        $ret = [];

        $userId = $this->self_get_option('superjob_user_id');
        if (empty($userId)) {
            return false;
        }

        do {
            $params = [
                'status' => 'all',
                'id_user' => $userId,
                'with_new_response' => 0,
                'order_field' => 'date',
                'order_direction' => 'desc',
                'page' => $page,
                'count' => 100
            ];
            $query = http_build_query($params);
            $res = $this->api_send("{$this->api_url}/hr/vacancies/?{$query}");
            $res_o = json_decode($res);

            if ($res === false || !is_object($res_o) || !isset($res_o->objects)) {
                return false;
            }

            $ret = array_merge($ret, $res_o->objects);

            if ($vid > 0) { // Для конкретной вакансии, иначе возвращаем все
                foreach ($res_o->objects as $value) {
                    if ($value->id == $vid) {
                        $found = $value;
                        break;
                    }
                }
            }

            $page++;
        } while ($found === false && $res_o->more);

        return $found !== false ? $found : $ret;
    }

    public function api_send($url) {
        // Implementation for sending API requests.
        // This is just a placeholder and should be replaced with actual implementation.
        return '';
    }

    public function self_get_option($option_name) {
        // Implementation for getting options.
        // This is just a placeholder and should be replaced with actual implementation.
        return '';
    }
}
?>