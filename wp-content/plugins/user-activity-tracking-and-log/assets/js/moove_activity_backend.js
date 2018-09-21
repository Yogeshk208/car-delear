(function($){
    $(document).ready(function(){
        $('.moove-activity-screen-meta .moove-activity-columns-tog').on('change',function(){
            var classname = '.column-' + $(this).val();
            if ( $(this).is(':checked') ) {
                $('.moove-form-container').find(classname).removeClass('hidden');
            } else {
                $('.moove-form-container').find(classname).addClass('hidden');
            }
            save_user_options( false );
        });
        function save_user_options( page_reload ) {
            var form_data = $('.moove-activity-screen-meta #screen-meta form').serialize();
            $.post(
                ajaxurl,
                {
                    action: "moove_activity_save_user_options",
                    form_data : form_data
                },
                function( msg ) {
                    if ( page_reload ) {
                        location.reload();
                    }
                }
            );
        }
        $(document).on('click','#moove-activity-screen-options-apply',function(e){
            e.preventDefault();
            save_user_options( true );

        });

        // delete backlink
        $('body').on('change', '.ma-checkbox', function (e) {
            var r = confirm("Are you sure you want to disable logging for this element? This will delete all past logged data.");
            if (r) {
              $('.ma-checkbox').prop('checked',true);
              return true;
            } else {
              $('.ma-checkbox').prop('checked',false);
              return false;
            }
        });
        $(document).on('change','input[name="moove-activity-dtf"]',function(){
          console.log($(this).val());
          if ( $(this).val() === 'c' ) {
            $('#screen-options-wrap .moove-activity-screen-ctm').removeClass('moove-hidden');
          } else {
            $('#screen-options-wrap .moove-activity-screen-ctm').addClass('moove-hidden');
          }
        });
        function moove_check_screen_options() {
          if ( $('.moove-activity-screen-meta #screen-meta').length > 0 ) {
            $('.moove-activity-screen-meta #screen-meta .moove-activity-columns-tog').each(function(){
              var classname = '.column-' + $(this).val();
              if ( $(this).is(':checked') ) {
                $('.moove-activity-log-report .load-more-container').find(classname).removeClass('hidden');
              } else {
                $('.moove-activity-log-report .load-more-container').find(classname).addClass('hidden');
              }
            });
          }
        }
        // ACCORDION SETTINGS
        function close_accordion_section() {
          $('.moove-accordion .moove-accordion-section-title').removeClass('active');
          $('.moove-accordion .moove-accordion-section-content').slideUp(300).removeClass('open');
        }

        $('.moove-accordion-section-title').click(function(e) {
          // Grab current anchor value
          var currentAttrValue = $(this).attr('href');

          if($(e.target).is('.active')) {
              close_accordion_section();
          }else {
              close_accordion_section();

              // Add active class to section title
              $(this).addClass('active');
              // Open up the hidden content panel
              $('.moove-accordion ' + currentAttrValue).slideDown(200).addClass('open');
          }

          e.preventDefault();
        });

        // LOAD MORE BUTTONS
        $('.moove-activity-log-report').on('click', '.button.load-more', function(e) {
          e.preventDefault();

          var id = '#' + $(this).parent().parent().find('table').attr('id')+' tbody',
              offset = parseInt($(this).attr('data-offset'))+1,
              link = $(this).attr('href')+'&offset='+offset;
          var $element = $(id);
          if ( $(this).closest('.moove-accordion-section-content').length > 0 ) {
            $element = $(this).closest('.moove-accordion-section-content').find('tbody');
          }

          $('.moove-activity-log-report .load-more-container').load(link +' '+id+' tr', function(){
            moove_check_screen_options();
            $element.append($('.moove-activity-log-report .load-more-container').html());
          });

          if ( offset == parseInt( $(this).attr('data-max') ) ) {
            $(this).hide();
          } else {
            $(this).attr('data-offset',offset);
          }
        });

        $('.moove-form-container').on('click', '#post-query-submit', function(e){
          e.preventDefault();
          var page_url = $(this).attr('data-pageurl'),
              selected_date = $('#filter-by-date option:selected').val(),
              selected_post_type = $('#post_types option:selected').val(),
              user_selected = $('#uid option:selected').val(),
              searched = $('#post-search-input').val();
          if ( $('#uid').length > 0 ) {
            var new_url = page_url + '&m=' + selected_date + '&cat=' + selected_post_type + '&uid=' + user_selected + '&s=' + searched;
          } else {
            var new_url = page_url + '&m=' + selected_date + '&cat=' + selected_post_type + '&s=' + searched;
          }
          window.location.replace( new_url );
        });

        // CONFIRM ON DISABLE/ENABLE logging
        $('select.moove-activity-log-settings').on('change', function() {
          if ($(this).val() == '0' && parseInt($(this).attr('data-postcount'))) {
            if (!confirm('Are you sure? \nYou have '+$(this).attr('data-postcount')+' posts, where are log data!')) {
                $(this).val('1'); //set back
                return;
            }
          }
        });

        $('.moove-activity-log-report').on('click', '#search-submit', function(e){
          e.preventDefault();
          var page_url = $(this).attr('data-pageurl'),
              selected_date = $('#filter-by-date option:selected').val(),
              selected_post_type = $('#post_types option:selected').val(),
              user_selected = $('#uid option:selected').val(),
              searched = $('#post-search-input').val();
          if ( $('#uid').length > 0 ) {
            var new_url = page_url + '&m=' + selected_date + '&cat=' + selected_post_type + '&uid=' + user_selected + '&s=' + searched;
          } else {
            var new_url = page_url + '&m=' + selected_date + '&cat=' + selected_post_type + '&s=' + searched;
          }

          window.location.replace( new_url );
        });

        //CLEAR LOGS BUTTON
        $('.moove-activity-log-report').on('click', '.clear-all-logs', function(e) {
          e.preventDefault();
          if (!confirm('Warning! \nThis will remove all activities from the database!')) {
            return;
          }

          var id = '.'+$(this).closest('table').attr('class')+' tbody',
              link = $(this).attr('href')+'&clear-all-logs=1';
          $('.moove-activity-log-report .load-more-container').load(link +' '+id+' tr', function(){
            $('#moove-activity-message-cnt').empty().html('<div id="message" class="error notice notice-error is-dismissible"><p>Activity Logs removed.</p></div>');
            $(this).closest('.moove-form-container').find('table tbody').empty().html('<tr class="no-items"><td class="colspanchange" colspan="7">No posts found.</td></tr>');
            $('#moove-activity-buttons-container').empty();
            $('.moove-activity-log-report .tablenav .displaying-num').hide();
            $(this).hide();
          });
        });

        $('.moove-activity-log-report').on('click', '.clear-log', function(e) {
          e.preventDefault();
          if (!confirm('Are you sure?')) {
            return;
          }
          $(this).hide();
          var id = '#'+$(this).parent().closest('table').attr('class')+' tbody',
              link = $(this).attr('href')+'&clear-log='+ $(this).attr('data-pid'),
              accordion_id = "#moove-accordion-" + $(this).attr('data-pid'),
              $post_title = $('.moove-accordion-section-title[href="' + accordion_id + '"');

          $('.moove-activity-log-report .load-more-container').load(link +' '+id+' tr', function(){
            $('#moove-activity-message-cnt').empty().html('<div id="message" class="error notice notice-error is-dismissible"><p>Activity Logs for <strong>' + $post_title.text() + '</strong> removed.</p></div>');
            $(accordion_id).slideToggle( 100, function(){
              $post_title.hide();
            });
          });
        });

    }); // end document ready



})(jQuery);
