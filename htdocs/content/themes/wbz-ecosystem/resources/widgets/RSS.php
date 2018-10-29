<?php

/**
 * Class RSS_Widget overrides WP_Widget_RSS
 */
class RSS_Widget extends WP_Widget
{
    public $url = "http://feeds.feedburner.com/dephasage";

    /**
     * Constructor.
     */
    public function __construct()
    {
        $widget_ops = [
            'description' => __('Entries from syndicate Déphasage RSS feed', THEME_TEXTDOMAIN),
            'customize_selective_refresh' => true
        ];

        $control_ops = ['width' => 400];

        parent::__construct('rss', __('RSS'), $widget_ops, $control_ops);
    }

    /**
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update($new_instance, $old_instance)
    {
        return $this->process($new_instance, $this->url);
    }

    /**
     *
     */
    private function process($widget_rss, $check_feed = true)
    {
        $items = (int) $widget_rss['items'];
        if ($items < 1 || 20 < $items) {
            $items = 10;
        }
        $offset = (int) $widget_rss['offset'];
        $url = esc_url_raw(strip_tags($this->url));
        $title = isset($widget_rss['title']) ? trim(strip_tags($widget_rss['title'])) : '';
        $show_image = isset($widget_rss['show_image']) ? $widget_rss['show_image'] : 0;
        $show_player = isset($widget_rss['show_player']) ? $widget_rss['show_player'] : 0;
        $show_list = isset($widget_rss['show_list']) ? $widget_rss['show_list'] : 0;
        $show_content = isset($widget_rss['show_content']) ? $widget_rss['show_content'] : 0;

        if ($check_feed) {
            $rss = fetch_feed($url);
            $error = false;
            $link = '';
            if (is_wp_error($rss)) {
                $error = $rss->get_error_message();
            } else {
                $link = esc_url(strip_tags($rss->get_permalink()));
                while (stristr($link, 'http') != $link)
                    $link = substr($link, 1);

                $rss->__destruct();
                unset($rss);
            }
        }

        return compact(
            'title',
            'url',
            'link',
            'items',
            'offset',
            'error',
            'show_image',
            'show_player',
            'show_list',
            'show_content'
        );
    }

    /**
     * @param array $args
     * @param array $instance
     */
    public function widget($args, $instance)
    {
        if (isset($instance['error']) && $instance['error']) return;

        $url = !empty($instance['url']) ? $instance['url'] : $this->url;
        while (stristr($url, 'http') != $url) {
            $url = substr($url, 1);
        }

        if (empty($url)) return;

        // self-url destruction sequence
        if (in_array(untrailingslashit($url), array(site_url(), home_url()))) return;

        $rss = fetch_feed($url);
        $title = $instance['title'];
        $desc = '';
        $link = '';

        if (!is_wp_error($rss)) {
            $desc = esc_attr(strip_tags(@html_entity_decode($rss->get_description(), ENT_QUOTES, get_option('blog_charset'))));
            if (empty($title)) {
                $title = strip_tags($rss->get_title());
            }
            $link = strip_tags($rss->get_permalink());
            while (stristr($link, 'http') != $link) {
                $link = substr($link, 1);
            }
        }

        if (empty($title)) {
            $title = !empty($desc) ? $desc : __('Unknown Feed');
        }

        /** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
        $title = apply_filters('widget_title', $title, $instance, $this->id_base);

        $url = strip_tags($url);
        $icon = includes_url('images/rss.png');
        if ($title) {
            $title = '
                <a class="rsswidget" href="' . esc_url($url) . '">
                    <img class="rss-widget-icon" style="border:0" width="14" height="14" src="' . esc_url($icon) . '" alt="RSS" />
                </a>
                <a class="rsswidget" href="' . esc_url($link) . '">' . esc_html($title) . '</a>
            ';
        }

        echo $args['before_widget'];
        if ($title) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        $this->output($rss, $instance);
        echo $args['after_widget'];

        if (!is_wp_error($rss)) {
            $rss->__destruct();
        }
        unset($rss);
    }

    /**
     * @param $rss
     * @param $args
     */
    private function output($rss, $args)
    {
        if (is_string($rss)) {
            $rss = fetch_feed($rss);
        } elseif (is_array($rss) && isset($rss['url'])) {
            $args = $rss;
            $rss = fetch_feed($rss['url']);
        } elseif (!is_object($rss)) {
            return;
        }

        if (is_wp_error($rss)) {
            if (is_admin() || current_user_can('manage_options')) {
                echo '<p><strong>' . __('RSS Error:') . '</strong> ' . $rss->get_error_message() . '</p>';
            }
            return;
        }

        $default_args = ['items' => 10, 'offset' => 0];
        $args = wp_parse_args($args, $default_args);

        $items = (int) $args['items'];
        $offset = $items == 1 ? (int) $args['offset'] : 0;

        if ($items < 1 || 20 < $items) {
            $items = 10;
        }

        $data = [];

        if (!$rss->get_item_quantity()) {
            echo '<ul><li>' . __('An error has occurred, which probably means the feed is down. Try again later.') . '</li></ul>';
            $rss->__destruct();
            unset($rss);
            return;
        }

        foreach ($rss->get_items($offset, $items) as $key => $item) {
            $link = $item->get_link();
            while (stristr($link, 'http') != $link) {
                $link = substr($link, 1);
            }

            $title = esc_html(trim(strip_tags($item->get_title())));
            if (empty($title)) {
                $title = __('Untitled');
            }

            $desc = @html_entity_decode($item->get_description(), ENT_QUOTES, get_option('blog_charset'));

            // TODO Improve splitting
            if (strstr($desc, '[')) {
                $desc = explode('[', $desc, 2);
                $base = $desc[0];
                $content = '';
                if ($args['show_list'] && $args['show_content']) {
                    $content = $desc[1];
                } elseif ($args['show_content']) {
                    $content = $desc[1];
                    $desc = explode('<p>', $desc[0]);
                    $base = $desc[1];
                } else {
                    $desc = explode('<b>', $desc[0]);
                    $base = $desc[0];
                }
            }

            $tags = '<p><a><br>';

            if ($args['show_image']) {
                $tags .= '<figure><img>';
            }

            if ($args['show_player']) {
                $tags .= '<figure><iframe>';
            }

            $summary = strip_tags($base, $tags);
            $summary = '<div class="rss-summary">'.
                           '<div class="rss-base">'.$summary.'</div>'.
                           '<div class="rss-content">'.esc_html(strip_tags($content)).'</div>'.
                        '</div>';

            /* Data assignation to loop through template */
            $data[$key]['link'] = esc_url(strip_tags($link));
            $data[$key]['title'] = $title;
            $data[$key]['summary'] = $summary;
        }

        echo view('rss-flux', compact('data'));
        $rss->__destruct();
        unset($rss);
    }

    /**
     * @param array $instance
     * @return string|void
     */
    public function form($instance)
    {
        if (empty($instance)) {
            $instance = [
                'title' => '',
                'url' => $this->url,
                'items' => 10,
                'offset' => 0,
                'error' => false,
                'show_summary' => true,
            ];
        }
        $instance['number'] = $this->number;

        $args['title'] = isset($args['title']) ? $args['title'] : '';
        $args['url'] = isset($args['url']) ? $args['url'] : $this->url;
        $args['items'] = isset($args['items']) ? (int) $args['items'] : 0;

        if ($args['items'] < 1 || 20 < $args['items']) {
            $args['items'] = 10;
        }

        $args['offset'] = isset($args['offset'], $args['items']) && $args['items'] == 1 ? (int) $args['offset'] : 0;

        if (!empty($args['error'])) {
            echo '<p class="widget-error"><strong>' . __('RSS Error:') . '</strong> ' . $args['error'] . '</p>';
        }

        $this->settings($instance);
    }

    /**
     *
     */
    private function settings($args, $inputs = null)
    {
        $default_inputs = ['url' => true, 'title' => true, 'items' => true, 'offset' => true, 'show_image' => true, 'show_player' => true, 'show_list' => true, 'show_content' => true];
        $inputs = wp_parse_args($inputs, $default_inputs);

        $args['title'] = isset($args['title']) ? $args['title'] : '';
        $args['url'] = isset($args['url']) ? $args['url'] : $this->url;
        $args['items'] = isset($args['items']) ? (int) $args['items'] : 0;
        $args['offset'] = isset($args['offset'], $args['items']) && $args['items'] == 1 ? (int) $args['offset'] : 0;

        if ($args['items'] < 1 || 20 < $args['items']) {
            $args['items'] = 10;
        }

        $args['show_image'] = isset($args['show_image']) ? (int) $args['show_image'] : (int) $inputs['show_image'];
        $args['show_player'] = isset($args['show_player']) ? (int) $args['show_player'] : (int) $inputs['show_player'];
        $args['show_list'] = isset($args['show_list']) ? (int) $args['show_list'] : (int) $inputs['show_list'];
        $args['show_content'] = isset($args['show_content']) ? (int) $args['show_content'] : (int) $inputs['show_content'];

        if (!empty($args['error'])) {
            echo '<p class="widget-error"><strong>' . __('RSS Error:') . '</strong> ' . $args['error'] . '</p>';
        }

        $esc_number = esc_attr($args['number']);

        // TODO Find a way to echo return a template
        if ($inputs['url']) : ?>
            <p class="url">
                <label for="rss-url-<?php echo $esc_number; ?>"><?php _e('Enter the RSS feed URL here:'); ?></label>
                <input class="widefat" id="rss-url-<?php echo $esc_number; ?>"
                       name="widget-rss[<?php echo $esc_number; ?>][url]" type="text"
                       value="<?php echo esc_url($this->url); ?>"
                       disabled />
            </p>
        <?php endif;
        if ($inputs['title']) : ?>
            <p class="title">
                <label for="rss-title-<?php echo $esc_number; ?>"><?php _e('Give the feed a title (optional):'); ?></label>
                <input class="widefat" id="rss-title-<?php echo $esc_number; ?>"
                       name="widget-rss[<?php echo $esc_number; ?>][title]" type="text"
                       value="<?php echo esc_attr($args['title']); ?>"
                       placeholder="Déphasage – Musiques hors cadres" />
            </p>
        <?php endif;
        if ($inputs['items']) : ?>
            <p class="items">
                <label for="rss-items-<?php echo $esc_number; ?>"><?php _e('How many items would you like to display?'); ?></label>
                <select id="rss-items-<?php echo $esc_number; ?>" name="widget-rss[<?php echo $esc_number; ?>][items]">
                    <?php for ($i = 1; $i <= 20; ++$i) {
                        echo "<option value='$i' " . selected($args['items'], $i, false) . ">$i</option>";
                    } ?>
                </select>
            </p>
        <?php endif;
        if ($inputs['offset']) : ?>
            <p class="offset" style="<?php echo $args['items'] == 1 ? '' : 'display:none;' ?>">
                <label for="rss-offset-<?php echo $esc_number; ?>"><?php _e('Which item would you like to display?'); ?></label>
                <select id="rss-offset-<?php echo $esc_number; ?>" name="widget-rss[<?php echo $esc_number; ?>][offset]">
                    <?php $j = 169;
                    for ($i = 0; $i < 20; ++$i) {
                        echo "<option value='$i' " . selected($args['offset'], $i, false) . ">#$j</option>";
                        $j--;
                    } ?>
                </select>
            </p>
        <?php endif;
        if ($inputs['show_image']) : ?>
            <p>
                <input id="rss-show-image-<?php echo $esc_number; ?>" name="widget-rss[<?php echo $esc_number; ?>][show_image]" type="checkbox" value="1" <?php checked( $args['show_image'] ); ?>/>
                <label for="rss-show-image-<?php echo $esc_number; ?>"><?php _e( 'Display item image?' ); ?></label>
            </p>
        <?php endif;
        if ($inputs['show_player']) : ?>
            <p>
                <input id="rss-show-player-<?php echo $esc_number; ?>" name="widget-rss[<?php echo $esc_number; ?>][show_player]" type="checkbox" value="1" <?php checked( $args['show_player'] ); ?>/>
                <label for="rss-show-player-<?php echo $esc_number; ?>"><?php _e( 'Display item player?' ); ?></label>
            </p>
        <?php endif;
        if ($inputs['show_list']) : ?>
            <p>
                <input id="rss-show-list-<?php echo $esc_number; ?>" name="widget-rss[<?php echo $esc_number; ?>][show_list]" type="checkbox" value="1" <?php checked( $args['show_list'] ); ?>/>
                <label for="rss-show-list-<?php echo $esc_number; ?>"><?php _e( 'Display item playlist?' ); ?></label>
            </p>
        <?php endif;
        if ($inputs['show_content']) : ?>
            <p>
                <input id="rss-show-text-<?php echo $esc_number; ?>" name="widget-rss[<?php echo $esc_number; ?>][show_content]" type="checkbox" value="1" <?php checked($args['show_content']); ?>/>
                <label for="rss-show-text-<?php echo $esc_number; ?>"><?php _e( 'Display item content?' ); ?></label>
            </p>
        <?php endif;
        foreach (array_keys($default_inputs) as $input) :
            if ('hidden' === $inputs[$input]) :
                $id = str_replace('_', '-', $input); ?>
                <input type="hidden"
                       id="rss-<?php echo esc_attr($id); ?>-<?php echo $esc_number; ?>"
                       name="widget-rss[<?php echo $esc_number; ?>][<?php echo esc_attr($input); ?>]"
                       value="<?php echo esc_attr($args[$input]); ?>"/>
            <?php
            endif;
        endforeach;
    }
}
