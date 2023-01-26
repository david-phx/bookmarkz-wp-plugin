<?php
/*
Plugin Name: Bookmarkz
Version: 1.0
Plugin URI: http://www.dzhan.ru/blog/bookmarkz/
Description: Плагин для быстрого добавления посетителями записей блога в сети социальных закладок. Не забудьте проверить <a href="options-general.php?page=bookmarkz/bookmarkz.php">настройки</a>.
Author: Джан
Author URI: http://www.dzhan.ru/
*/

$version = '1.0';

$bookmarkz = array ('google' => array('title' => 'Google Bookmarks', 'uri' => 'http://www.google.com/bookmarks/mark?op=add&bkmk=<LINK>&title=<TITLE>'),
                    'digg' => array('title' => 'Digg', 'uri' => 'http://digg.com/submit?url=<LINK>'),
                    'reddit' => array ('title' => 'Reddit', 'uri' => 'http://reddit.com/submit?url=<LINK>&title=<TITLE>'),
                    'delicious' => array('title' => 'del.icio.us', 'uri' => 'http://del.icio.us/post?url=<LINK>&title=<TITLE>'),
                    'magnolia' => array('title' => 'Ma.gnolia', 'uri' => 'http://ma.gnolia.com/beta/bookmarklet/add?url=<LINK>&title=<TITLE>&description=<TITLE>'),
                    'technorati' => array ('title' => 'Technorati', 'uri' => 'http://www.technorati.com/faves?add=<LINK>'),
                    'slashdot' => array ('title' => 'Slashdot', 'uri' => 'http://www.slashdot.org/bookmark.pl?url=<LINK>&title=<TITLE>'),
                    'yahoo' => array ('title' => 'Yahoo My Web', 'uri' => 'http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<LINK>&t=<TITLE>'),
                    'news2ru' => array ('title' => 'News2.ru', 'uri' => 'http://news2.ru/add_story.php?url=<LINK>'),
                    'bobrdobr' => array ('title' => 'БобрДобр.ru', 'uri' => 'http://www.bobrdobr.ru/addext.html?url=<LINK>&title=<TITLE>'),
                    'rumarkz' => array ('title' => 'RUmarkz', 'uri' => 'http://rumarkz.ru/bookmarks/?action=add&popup=1&address=<LINK>&title=<TITLE>'),
                    'vaau' => array ('title' => 'Ваау!', 'uri' => 'http://www.vaau.ru/submit/?action=step2&url=<LINK>'),
                    'memori' => array ('title' => 'Memori.ru', 'uri' => 'http://memori.ru/link/?sm=1&u_data[url]=<LINK>&u_data[name]=<TITLE>'),
                    'rucity' => array ('title' => 'rucity.com', 'uri' => 'http://www.rucity.com/bookmarks.php?action=add&address=<LINK>&title=<TITLE>'),
                    'moemesto' => array ('title' => 'МоёМесто.ru', 'uri' => 'http://moemesto.ru/post.php?url=<LINK>&title=<TITLE>'),
                    'mrwong' => array ('title' => 'Mister Wong', 'uri' => 'http://www.mister-wong.ru/index.php?action=addurl&bm_url=<LINK>&bm_description=<TITLE>')
                   );

$images_path = get_bloginfo('siteurl') . '/wp-content/plugins/bookmarkz/images/';
$separator = ' ';

// заводим дефолтные значения, если запускаемся в первый раз
// использовать ручное добавление кода
add_option('bookmarkz_manual_insert', FALSE, 'Bookmarkz Plugin');
// использовать rel="nofollow"
add_option('bookmarkz_use_nofollow', TRUE, 'Bookmarkz Plugin');
// использовать target="_blank"
add_option('bookmarkz_target_blank', TRUE, 'Bookmarkz Plugin');
    
// какие ссылки отображать, по дефолту все включены
foreach ($bookmarkz as $key => $value) {
    $option_name = 'bookmarkz_show_' . $key;
    add_option($option_name, TRUE, 'Bookmarkz Plugin');
}

$manual_insert = get_option('bookmarkz_manual_insert');
$use_nofollow = get_option('bookmarkz_use_nofollow');
$target_blank = get_option('bookmarkz_target_blank');

function getBookmarkLink ($service, $post_link, $post_title)
{
    global $bookmarkz, $images_path, $use_nofollow, $target_blank;
    $link_uri = preg_replace("|<LINK>|", $post_link, $bookmarkz[$service]['uri']);
    $link_uri = preg_replace("|<TITLE>|", $post_title, $link_uri);
    $img = '<img src="' . $images_path . $service . '.png" border="0" width="16" height="16" alt="' . $bookmarkz[$service]['title'] . '" title="' . $bookmarkz[$service]['title'] . '">';
    $link = '<a href="' . $link_uri . '"';
    if ($use_nofollow) $link .= ' rel="nofollow"';
    if ($target_blank) $link .= ' target="_blank"';
    $link .= '>' . $img . '</a>';
    return $link;
}

function bookmarkz($text = '')
{
    global $post, $manual_insert, $bookmarkz, $separator;
    $post_title = urlencode(stripslashes($post->post_title) . ' - ' . get_bloginfo('name') );
	$post_link = get_permalink($post->ID);
    
    $bookmark_list = "\n" . '<div class="bookmarkz">';
    foreach ($bookmarkz as $key => $value) {
        if (get_option('bookmarkz_show_' . $key)) {
            $bookmark_list .= getBookmarkLink($key, $post_link, $post_title) . $separator;
        }
    }
    $bookmark_list .= "</div>\n";
   
    if ($manual_insert) {
        echo $bookmark_list;
        return true;
    } else {
        return $text . $bookmark_list;
    }
}

if (!$manual_insert) add_action('the_content', 'bookmarkz');

function getLatestVersion()
{
    $fp = fsockopen ("www.dzhan.ru", 80);

    $headers = "GET /files/plugins/bookmarkz.txt HTTP/1.1\r\n";
    $headers .= "Host: www.dzhan.ru\r\n";
    $headers .= "Connection: Close\r\n\r\n";

    fwrite ($fp, $headers);
    $str = '';

    while (!feof ($fp))
    {
     $str .= fgets($fp, 1024);
    }

    fclose($fp);
    
    $latest_version = 'неизвестна';
    if (strpos($str, 'bookmarkzver:') != FALSE) { $latest_version = substr($str,strpos($str, 'bookmarkzver:') + 13); }
    return $latest_version;
}

function bookmarkzOptionsPage()
{
    global $version, $bookmarkz, $images_path;
    
	if (isset($_POST['update_options'])) {
		// обновляем настройки
		if (isset($_POST['manual_insert'])) {
            update_option('bookmarkz_manual_insert', $manual_insert = TRUE);	
		} else {
		    update_option('bookmarkz_manual_insert', $manual_insert = FALSE);
		}
        
		if (isset($_POST['use_nofollow'])) {
            update_option('bookmarkz_use_nofollow', $use_nofollow = TRUE);	
		} else {
		    update_option('bookmarkz_use_nofollow', $use_nofollow = FALSE);
		}

		if (isset($_POST['target_blank'])) {
            update_option('bookmarkz_target_blank', $target_blank = TRUE);	
		} else {
		    update_option('bookmarkz_target_blank', $target_blank = FALSE);
		}
	
        foreach ($bookmarkz as $key => $value) {
            if (isset($_POST['show_' . $key])) {
                $option_name = 'bookmarkz_show_' . $key;
                update_option($option_name, $show[$key] = TRUE);
            } else {
                $option_name = 'bookmarkz_show_' . $key;
                update_option($option_name, $show[$key] = FALSE);
            }
        }

	} else {
		// загружаем текущие настройки из базы
		$manual_insert = get_option('bookmarkz_manual_insert');
		$use_nofollow = get_option('bookmarkz_use_nofollow');
		$target_blank = get_option('bookmarkz_target_blank');

        foreach ($bookmarkz as $key => $value) {
            $option_name = 'bookmarkz_show_' . $key;
            $show[$key] = get_option($option_name);
        }
	}

?>
<div class="wrap">
    <h2>Настройки Bookmarkz</h2>
    <?php $latest_version = getLatestVersion(); ?>
    <p><?php if ($version == $latest_version) { ?>
    У вас установлена свежая версия плагина: <strong><?php echo $version ?></strong>.
    <?php } else { ?>  
    Ваша верcия плагина: <?php echo $version ?>, текущая: <strong><?php echo $latest_version; ?></strong>.
    Возможно, есть смысл <a href="http://www.dzhan.ru/blog/bookmarkz/">обновить плагин</a>?<?php } ?></p>
    
    <form method="post">

    <fieldset class="options">
        <legend>Отображение ссылок:</legend>
        <p>
        
        <?php
        foreach ($bookmarkz as $key => $value) {
        ?>
        <label>
        <input name="show_<?php echo $key; ?>" type="checkbox" <?php checked(TRUE, $show[$key]); ?> class="tog"/>
        <img src="<?php echo $images_path . $key; ?>.png" width="16" height="16" border="0" align="absmiddle"> <?php echo $bookmarkz[$key]['title']; ?>
        </label><br /></p><p>
        
        <?php
        }
        ?>
		</p>
    </fieldset>
   
    <p class="submit"><input type="submit" class="submit" name="update_options" value="Сохранить настройки &raquo;" /></p>
    
	<fieldset class="options">
		<legend>Настройки кода:</legend>

        <p><label>
        <input name="manual_insert" type="checkbox" <?php checked(TRUE, $manual_insert); ?> class="tog"/>
		Использовать ручную вставку кода.<br /> <br />
        Плагин автоматически добавляет иконки со ссылками на популярные сети социальных закладок
        в конец каждого сообщения в блоге. Если вы хотите вручную управлять отображением иконок, вставьте в свой шаблон
        следующий кусок кода: <em>&lt;?php bookmarkz(); ?&gt;</em> там, где вы хотите вывести иконки. Обратите внимание,
        что этот код должен быть размещен внутри цикла <em>TheLoop</em>, т.е. между
        <em>&lt;?php while (have_posts()) : the_post(); ?&gt;</em> и <em>&lt;?php endwhile; ?&gt;</em><br /> <br />
        Управлять внешним видом иконок можно через определение класса <em>div.bookmarkz</em> в файле
        <em>style.css</em> вашего шаблона, например, так:<br />
        <em>div.bookmarkz {text-align: center; margin: 10px 0;}</em>
        </label></p>

        <p><label>
        <input name="use_nofollow" type="checkbox" <?php checked(TRUE, $use_nofollow); ?> class="tog"/>
		Использовать аттрибут <em>rel="nofollow"</em>.<br /> <br />
        Указанный аттрибут запрещает поисковым системам переход по ссылке. Если вы не уверены, нужно это вам или нет,
        просто оставьте как есть.
        </label></p>

        <p><label>
        <input name="target_blank" type="checkbox" <?php checked(TRUE, $target_blank); ?> class="tog"/>
		Открывать ссылки в новом окне.
        </label></p>
        
	</fieldset>
		
    <p class="submit"><input type="submit" class="submit" name="update_options" value="Сохранить настройки &raquo;" /></p>
    </form>	
</div>
<?php
}

function boomarkzAddMenu() {
		add_options_page('Bookmarkz', 'Bookmarkz', 8, __FILE__, 'bookmarkzOptionsPage');
}

add_action('admin_menu', 'boomarkzAddMenu');

?>
