(function($) {
    
    $.fn.columnControl = function(targetTable, storageKey) {

        var modalId = 'columnControl_' + Math.round(Math.random() * 100000).toString();
        
        var storage = localStorage;
        var saveKey = window.location.pathname + '.' + (storageKey || $(this).text());

        $(function() {
            
            $('body').append(`<div id="${modalId}" class="modal-container colmun_setting">
                <div class="modal-body">
                    <!-- 閉じるボタン -->
                    <a class="modal-close btn grey_line">×</a>
                    <!-- モーダル内のコンテンツ -->
                    <div class="modal-content multiple_checkbox">
                        <h4>検索結果表示設定</h4>
                        <a href="#" class="view_colmun_reset">初期設定</a>
                        <label>
                            <input type="checkbox" class="all_check view_colmun">
                            全て
                        </label>
                    </div>
                </div>
            </div>
            <style>
                /* modal style */
                /*モーダルを開くボタン*/
                .modal-open{
                    position: fixed;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    top: 50%;
                    left: 50%;
                    width: 300px;
                    height: 50px;
                    font-weight: bold;
                    color: #fff;
                    background: #000;
                    margin: auto;
                    cursor: pointer;
                    transform: translate(-50%,-50%);
                }
                /*モーダル本体の指定 + モーダル外側の背景の指定*/
                .modal-container{
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    text-align: center;
                    background: rgba(0,0,0,50%);
                    padding: 40px 20px;
                    overflow: auto;
                    opacity: 0;
                    visibility: hidden;
                    transition: .3s;
                    box-sizing: border-box;
                }
                /*モーダル本体の擬似要素の指定*/
                .modal-container:before{
                    content: "";
                    display: inline-block;
                    vertical-align: middle;
                    height: 100%;
                }
                /*モーダル本体に「active」クラス付与した時のスタイル*/
                .modal-container.active{
                    opacity: 1;
                    visibility: visible;
                }
                /*モーダル枠の指定*/
                .modal-body{
                    position: relative;
                    display: inline-block;
                    vertical-align: middle;
                    max-width: 500px;
                    width: 90%;
                }
                /*モーダルを閉じるボタンの指定*/
                .modal-close{
                    position: absolute;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    top: -20px;
                    right: -20px;
                    width: 40px;
                    height: 40px;
                    font-size: 40px;
                    color: #999999;
                    background: #fff;
                    border: #999999 solid 2px;
                    border-radius: 1em;
                    vertical-align: 10px;
                    cursor: pointer;
                }
                /*モーダル内のコンテンツの指定*/
                .modal-content{
                    background: #fff;
                    text-align: left;
                    padding: 30px;
                    border: #999999 solid 2px;
                    border-radius: 1em;
                }
            </style>`);
        
            $(targetTable).find('tr:first').find('th').each(function(i, e) {
                
                var col_label = $(e).text();
                // $(e).data('col_label', col_label).data('is_show', $(e).data('is_show') === 0 ? 0 : 1);
                $(e).attr('data-col_label', col_label).attr('data-is_show', $(e).attr('data-is_show') === '0' ? 0 : 1);
                
                // alert($(e).data('col_label') + ':::' + $(e).data('is_show'));
                
                $(targetTable).find('tr:not(:first)').each(function() {
                    
                    // $(this).find('td').eq(i).data('col_label', col_label);
                    
                    $(this).find('td').eq(i).attr('data-col_label', col_label);
                    
                    // alert($(this).find('td').eq(i).data('col_label'));
                });
            });
            
            if (storage.getItem(saveKey) === null) {
                // 初期値設定
                storage.setItem(saveKey, JSON.stringify($(targetTable).find('tr:first').find('th[data-is_show="1"]').map(function() {
                    return $(this).data('col_label');
                })));
            }
            // 初期表示
            var listShowColmuns =  Object.values(JSON.parse(storage.getItem(saveKey)));
            

            console.dir(listShowColmuns);

            $(targetTable).find('[data-col_label]').hide();
            $(targetTable).find('th[data-col_label]').each(function () {

                var val = $(this).data('col_label');

                console.log(val);
                $('#' + modalId).find('.multiple_checkbox')
                    .append(`<label style="display:inline-block;">
                        <input 
                            type="checkbox" 
                            class="view_colmun" 
                            value="${val}"
                            ${(listShowColmuns.includes(val) ? ' checked' : '')}
                        >${val}</label>`
                    );

                listShowColmuns.includes(val) 
                    && $(targetTable).find('[data-col_label="' + val + '"]').show();
            });
        });

        var settingReflection = function() {

            $(targetTable).find('[data-col_label]').hide();
            var listShowColmuns = $('#' + modalId).find('.multiple_checkbox .view_colmun:checked').map(function() {

                var val = $(this).val();
                $(targetTable).find('[data-col_label="' + val + '"]').show();

                return val;
            });
            storage.setItem(saveKey, JSON.stringify(listShowColmuns));
        };

        $('body').on('click', '.show_colmun_setting', function() {

            $('.modal-container.colmun_setting').addClass('active');
            return false;
        }).on('click', '.colmun_setting .modal-close', function() {

            $('.modal-container.colmun_setting').removeClass('active');
            return false;
        }).on('click', '.view_colmun.all_check', function() {

            $('.view_colmun:not(.all_check)').prop('checked', $(this).prop('checked'));

            settingReflection();
        }).on('click', '.view_colmun:not(.all_check)', function() {

            $('.view_colmun.all_check').prop('checked', $('.view_colmun:not(.all_check)').length === $('.view_colmun:not(.all_check)').filter(':checked').length);

            settingReflection();
        }).on('click', '.view_colmun_reset', function() {

            $('.view_colmun').prop('checked', false);
            $('[data-is_show="1"]').each(function() {

                $('.view_colmun[value="' + $(this).data('col_label') + '"]').prop('checked', true);
            });
            $('.view_colmun.all_check').prop('checked', $('.view_colmun:not(.all_check)').length === $('.view_colmun:not(.all_check)').filter(':checked').length);

            settingReflection();

            return false;
        });
        
        
        
        
        
    };
})(jQuery);