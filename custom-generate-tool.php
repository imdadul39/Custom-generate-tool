<?php

/**
 * Plugin Name: Custom Generate Tool
 * Description: YouTube creates EMBED links to start and end at specific times in the video and Social share option.
 * Version: 1.0.1
 * Author: Imdadul Haque
 * Author URI: https://github.com/imdadul39
 */

if (!defined('ABSPATH')) {
    exit;
}

class YouTubeTimestampEmbedGenerator
{
    public function __construct()
    {
        add_shortcode('youtube_link_generator', [$this, 'render_shortcode']);
        add_shortcode('ss_share_link_generator', [$this, 'ss_social_render_shortcode']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    // CSS & JS
    public function enqueue_scripts()
    {
        wp_enqueue_style('yt-embed-generator-style', plugin_dir_url(__FILE__) . 'assets/css/style.css');
        wp_enqueue_style('ss-style', plugin_dir_url(__FILE__) . 'assets/css/ss-style.min.css');
        wp_enqueue_script('yt-embed-generator-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', [], false, true);
        wp_enqueue_script('ss-custom-script', plugin_dir_url(__FILE__) . 'assets/js/ss-custom.js', [], false, true);
        wp_enqueue_script('ss-script', plugin_dir_url(__FILE__) . 'assets/js/ss-script.min.js', [], false, true);
    }

    // Shortcode Render Function
    public function render_shortcode()
    {
        ob_start();
?>
        <div class="yt-embed-generator">
            <h3>YouTube start time link generator</h3>

            <label> Paste your YouTube URL:</label>
            <input type="text" id="yt_video_url" placeholder="https://www.youtube.com/watch?v=VIDEO_ID">

            <label> Start Time:</label>
            <div>
                <input type="number" id="start_minutes" placeholder="Minutes" min="0">
                <input type="number" id="start_seconds" placeholder="Seconds" min="0" max="59">
            </div>

            <label> End Time (optional):</label>
            <div>
                <input type="number" id="end_minutes" placeholder="Minutes" min="0">
                <input type="number" id="end_seconds" placeholder="Seconds" min="0" max="59">
            </div>

            <button onclick="generateEmbedLink()">Generate Link</button>

            <p>Generated Link:
                <input type="text" id="generated_embed_link" readonly>
                <button onclick="copyEmbedLink()">Copy</button>
            </p>

            <p id="copy_message" style="color: green; display: none;">Link Copied!</p>

            <iframe id="yt_embed" width="400" height="225" style="display: none;" frameborder="0" allowfullscreen></iframe>
        </div>
    <?php
        return ob_get_clean();
    }


    // ****************************************************

    public function ss_social_render_shortcode()
    {
        ob_start();
    ?>
        <section id="body">
            <div id="page" class="site">
                <div id="content" class="site-content">
                    <div id="st-main">
                        <form id="st-body">
                            <div class="st-container">
                                <h3 class="st-body-title">Select social media</h3>
                                <div id="st-filters">
                                    <input type="radio" id="st-all" name="social" value="all" class="st-filter-input"
                                        checked="checked">
                                    <label for="st-all" class="st-filter-all st-filter" data-id="all">All</label>
                                    <input type="radio" id="st-fb" name="social" value="fb" class="st-filter-input">
                                    <label for="st-fb" class="st-filter-fb st-filter" data-id="fb"><svg
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            viewBox="0 0 512 512" aria-label="fb" role="img">
                                            <path
                                                d="m375.14,288l14.22,-92.66l-88.91,0l0,-60.13c0,-25.35 12.42,-50.06 52.24,-50.06l40.42,0l0,-78.89s-36.68,-6.26 -71.75,-6.26c-73.22,0 -121.08,44.38 -121.08,124.72l0,70.62l-81.39,0l0,92.66l81.39,0l0,224l100.17,0l0,-224l74.69,0z" />
                                        </svg></label>
                                    <input type="radio" id="st-tw" name="social" value="tw" class="st-filter-input">
                                    <label for="st-tw" class="st-filter-tw st-filter" data-id="tw"><svg
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            viewBox="0 0 512 512" aria-label="tw" role="img">
                                            <path
                                                d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                                        </svg></label>
                                    <input type="radio" id="st-pn" name="social" value="pn" class="st-filter-input">
                                    <label for="st-pn" class="st-filter-pn st-filter" data-id="pn"><svg
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            viewBox="0 0 512 512" aria-label="pn" role="img">
                                            <path
                                                d="m511,255.99999c0,140.86694 -114.13307,255.00001 -255.00001,255.00001c-26.32258,0 -51.61694,-4.01008 -75.47178,-11.41331c10.38508,-16.96573 25.91129,-44.72782 31.66936,-66.83468c3.08468,-11.92742 15.83468,-60.66532 15.83468,-60.66532c8.32863,15.83468 32.59476,29.30444 58.40323,29.30444c76.91129,0 132.33267,-70.74194 132.33267,-158.65525c0,-84.2117 -68.78831,-147.24194 -157.21573,-147.24194c-110.02017,0 -168.52622,73.82662 -168.52622,154.3367c0,37.42742 19.94758,84.00605 51.71976,98.8125c4.83266,2.2621 7.40323,1.23387 8.53427,-3.39315c0.82258,-3.49597 5.14113,-20.87298 7.09476,-28.89315c0.61694,-2.57056 0.30847,-4.83266 -1.74798,-7.3004c-10.38508,-12.85282 -18.81653,-36.29637 -18.81653,-58.19758c0,-56.24395 42.56855,-110.6371 115.16129,-110.6371c62.61895,0 106.5242,42.67137 106.5242,103.74799c0,68.99395 -34.85686,116.80646 -80.20162,116.80646c-24.98589,0 -43.80242,-20.66734 -37.73589,-46.06452c7.19758,-30.33266 21.07863,-63.03024 21.07863,-84.93145c0,-19.53629 -10.4879,-35.88508 -32.28629,-35.88508c-25.60282,0 -46.16734,26.4254 -46.16734,61.8992c0,22.62097 7.60887,37.83871 7.60887,37.83871s-25.19153,106.72984 -29.81855,126.67742c-5.14113,22.00403 -3.08468,53.05645 -0.9254,73.20968c-94.80242,-37.11895 -162.04839,-129.45363 -162.04839,-237.52017c0,-140.86694 114.13307,-255.00001 255.00001,-255.00001s255.00001,114.13307 255.00001,255.00001z" />
                                        </svg></label>
                                    <input type="radio" id="st-ln" name="social" value="ln" class="st-filter-input">
                                    <label for="st-ln" class="st-filter-ln st-filter" data-id="ln"><svg
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            viewBox="0 0 512 512" aria-label="ln" role="img">
                                            <path
                                                d="m132.28,479.99501l-92.88,0l0,-299.1l92.88,0l0,299.1zm-46.49,-339.9c-29.7,0 -53.79,-24.6 -53.79,-54.3a53.79,53.79 0 0 1 107.58,0c0,29.7 -24.1,54.3 -53.79,54.3zm394.11,339.9l-92.68,0l0,-145.6c0,-34.7 -0.7,-79.2 -48.29,-79.2c-48.29,0 -55.69,37.7 -55.69,76.7l0,148.1l-92.78,0l0,-299.1l89.08,0l0,40.8l1.3,0c12.4,-23.5 42.69,-48.3 87.88,-48.3c94,0 111.28,61.9 111.28,142.3l0,164.3l-0.1,0z" />
                                        </svg></label>
                                    <input type="radio" id="st-mail" name="social" value="mail" class="st-filter-input">
                                    <label for="st-mail" class="st-filter-mail st-filter" data-id="mail"><svg
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                            viewBox="0 0 512 512" aria-label="mail" role="img">
                                            <path
                                                d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z" />
                                        </svg></label>
                                </div>
                                <div id="st-field-url" class="st-field">
                                    <input type="text" class="st-input st-input-url" name="st-url" id="st-url">
                                    <label for="st-url" class="st-label"><strong>URL</strong> (Paste here the url you want to
                                        share)</label>
                                    <div class="error-message error-badurl">Wrong url. Check your link!</div>
                                    <div class="error-message error-emptyurl">This field is required</div>
                                    <div class="st-field-desc">Example: https://kylemccarthy.com</div>
                                </div>
                                <div id="st-field-text" class="st-field">
                                    <textarea class="st-textarea" name="text" id="st-text" rows="5"></textarea>
                                    <label for="st-text" class="st-label"><strong>Text (Optional)</strong> (Twitter & Pinterest
                                        only)</label>
                                </div>

                                <button type="submit" class="st-submit" id="st-submit">create links!</button>
                            </div>
                        </form>
                        <div id="st-share-section">
                            <div class="st-container">
                                <div id="st-share-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="st-more-section">
                        <div class="st-container">
                            <form id="st-more" class="st-more-item"></form>
                        </div>
                    </div>
                    <label id="st-hidden-label" for="input-copy"><span style="display:none">1</span>
                        <input type="text" id="input-copy" value="">
                    </label>

                    <section class="new-post-row new-post-section-claps">
                        <div id="post-single-trid">161016</div>
                        <div id="post-single-id">161016</div>

                        <div class="new-post-container new-post-section-stars">

                            <div class="rating rating-article not-active" id="new-post-stars-rating" data-tip1="Awful"
                                data-tip2="Poor" data-tip3="Ok" data-tip4="Good" data-tip5="Great" data-vote="" data-val="1"
                                data-message="Thanks for your feedback!" data-sorry="Sorry about that!">
                                <div id="new-post-stars-rating-tooltip"></div>
                            </div>
                            <div class="new-post-section-stars-count">


                            </div>
                            <div class="new-post-section-stars-form">
                                <form action="" id="new-post-section-stars-form">
                                    <label for="new-post-section-stars-form-message">
                                        <textarea class="new-post-section-stars-form-text"
                                            id="new-post-section-stars-form-message" name="new-post-section-stars-form-text"
                                            placeholder="How can we make this tool better?"></textarea>
                                        <span style="display:none;">1</span></label>
                                    <span class="new-post-form-error-message text-error"
                                        id="new-post-section-stars-form-required">This field is required</span>
                                    <span class="new-post-form-error-message text-error"
                                        id="new-post-section-stars-form-big">Maximal length of comment is equal 80000
                                        chars</span>
                                    <span class="new-post-form-error-message text-error"
                                        id="new-post-section-stars-form-small">Minimal length of comment is equal 10
                                        chars</span>
                                    <label for="stars-form-review-email">
                                        <input type="text" name="stars-form-review-email" id="stars-form-review-email"
                                            class="new-post-section-stars-form-text" placeholder="Your Email" />
                                        <span style="display:none;">1</span></label>
                                    <span class="new-post-form-error-message email-error"
                                        id="stars-form-review-email-error-required">The email is required</span>
                                    <span class="new-post-form-error-message email-error"
                                        id="stars-form-review-email-error-incorrect">The email is incorrect</span>
                                    <div class="new-post-section-starss-form-submit">
                                        <button id="new-post-section-stars-form-button"
                                            class="new-post-section-stars-form-button">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </section>
                    <div id="clap-popup-bg">
                        <div class="clap-popup-bg-inner">
                            <div class="clap-popup-container">
                                <span class="clap-popup-close" id="clap-popup-close"></span>
                                <div class="new-comment-final-window-share-items new-post-row">
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&title=sharelink&summary=&source=kylemccarthy&url=https://https://kylemccarthy.com//webtools/sharelink/"
                                        target="_blank" rel="nofollow noopener noreferrer"
                                        class="new-comment-final-window-share-item new-comment-final-window-share-item-ln">
                                        <svg viewBox="0 0 448 512">
                                            <path fill="currentColor"
                                                d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z">
                                            </path>
                                        </svg>
                                        <span style="display:none">1</span></a>
                                    <a href="http://twitter.com/share?url=https%3A%2F%2Fhttps://kylemccarthy.com/%2Fwebtools%2Fsharelink%2F"
                                        target="_blank" rel="nofollow noopener noreferrer"
                                        class="new-comment-final-window-share-item new-comment-final-window-share-item-tw">
                                        <svg viewBox="0 0 512 512">
                                            <path fill="currentColor"
                                                d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z" />
                                        </svg>
                                        <span style="display:none">1</span></a>
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=https%3A%2F%2Fhttps://kylemccarthy.com/%2Fwebtools%2Fsharelink%2F%3Fvendordirectcomment%3D"
                                        target="_blank" rel="nofollow noopener noreferrer"
                                        class="new-comment-final-window-share-item new-comment-final-window-share-item-fb">
                                        <svg viewBox="0 0 320 512">
                                            <path fill="currentColor"
                                                d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z">
                                            </path>
                                        </svg>
                                        <span style="display:none">1</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
<?php
        return ob_get_clean();
    }
}

// Plugin Init
new YouTubeTimestampEmbedGenerator();
