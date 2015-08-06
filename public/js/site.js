(function($) {
    $.fn.shorten = function (settings) {
        var config = {
            showChars: 300,
            ellipsesText: "...",
            moreText: "显示全部",
            lessText: "收起"
        };
        if (settings) {
            $.extend(config, settings);
        }
        $(document).off("click", '.morelink');
        $(document).on({click: function () {
            var $this = $(this);
            if ($this.hasClass('less')) {
                $this.removeClass('less');
                $this.html(config.moreText);
            } else {
                $this.addClass('less');
                $this.html(config.lessText);
            }
            $this.parent().prev().toggle();
            $this.prev().toggle();
            return false;
        }
        }, '.morelink');
        return this.each(function () {
            var $this = $(this);
            if($this.hasClass("shortened")) return;
            $this.addClass("shortened");
            var content = $this.html();
            if (content.length > config.showChars) {
                var c = content.substr(0, config.showChars);
                var h = content.substr(config.showChars, content.length - config.showChars);
                var html = c + '<span class="moreellipses">' + config.ellipsesText + ' </span><span class="morecontent"><span>' + h + '</span> <a href="#" class="morelink">' + config.moreText + '</a></span>';
                $this.html(html);
                $(".morecontent span").hide();
            }
        });
    };
})(jQuery);

$(".review-content").shorten();
$(".introduction").shorten({showChars: 150});

$('#dateTab a:first').tab('show');
$('#dateTab a').hover(function () {
    $(this).tab('show');
});

$('#episodeTab a:first').tab('show');
$('#episodeTab a').click(function (e) {
    e.preventDefault();
    $('#carousel').carousel($(this).parent().index());
    $(this).tab('show');
});

$(function() {
    $(window).scroll(function() {
        if($(this).scrollTop() != 0) {
            $("#toTop").fadeIn();
        } else {
            $("#toTop").fadeOut();
        }
    });
    $("#toTop").click(function() {
        $("body,html").animate({scrollTop:0});
    });
});

$(function() {
    $('#title').blur(function() {
        var title = $(this).val();
        if(title != '') {
            $.ajax({
                type: 'GET',
                url: "/drama/search",
                data: {title: title},
                dataType: 'json',
                success: function(data) {
                    var dramas = '已有如下同名剧集：<br>';
                    $.each(data, function(i, n) {
                        dramas += '剧集id：<a href="/drama/'+n['id']+'" target="_blank">'+n['id']+'</a> 主役CV：'+n['sc']+'<br>';
                    });
                    $('#dramas').html(dramas);
                },
                error: function(xhr, type) {
                    alert(xhr);
                    alert(type);
                }
            });
        }
    });
});

$("#favoriteEdit :radio").change(function() {
    var type = $("input[name='type']:checked").val();
    if (type == "0") {
        $("#ratingEdit").hide();
    }
    else {
        $("#ratingEdit").show();
    }
});

$('#favModal').on('show.bs.modal', function (event) {
    var method = $(event.relatedTarget).data('method');
    var modal = $(this);
    var idname = $(event.relatedTarget).data('idname');
    if(idname == "episode_id") {
        modal.find("input[name='type']").eq(1).parent().hide();
        modal.find("input[name='type']").eq(3).parent().hide();
    }
    else {
        modal.find("input[name='type']").eq(1).parent().show();
        modal.find("input[name='type']").eq(3).parent().show();
    }
    if (method == 'POST') {
        modal.find("input[name='_method']").prop('name', 'none');
        modal.find("input[name='id']").prop('value', $(event.relatedTarget).data('idvalue'));
        modal.find("input[name='id']").prop('name', idname);
        modal.find("input[name='type']").eq(2).prop('checked', true);
        modal.find("input[name='rating']").rating('update', 0);
    }
    else if (method == 'PUT') {
        modal.find("input[name='none']").prop('name', '_method');
        var favorite = $(event.relatedTarget).data('favorite');
        modal.find("input[name='type']").eq(favorite.type).prop('checked', true);
        modal.find("input[name='rating']").rating('update', favorite.rating);
    }
    modal.find("form").prop('action', $(event.relatedTarget).data('action'));
});

$('#deleteConfirmModal').on('show.bs.modal', function (event) {
    var action = $(event.relatedTarget).data('action');
    $(this).find("form").prop('action', action);
});

$('#carousel').carousel({
    interval: false
});

$("#jobs :checkbox").change(function() {
    var jobs = $("input[name='jobs']");
    var job = $.trim($(this).parent().text());
    if ($(this).prop('checked') == true) {
        if (jobs.val() == '') {
            jobs.prop('value', job);
        }
        else {
            jobs.prop('value', jobs.val() + '，' + job);
        }
    }
    else {
        jobs.prop('value', jobs.val().replace('，' + job, ''));
        jobs.prop('value', jobs.val().replace(job + '，', ''));
        jobs.prop('value', jobs.val().replace(job, ''));
    }
});

$('.rating').rating({
    clearCaption: '未评分',
    starCaptions: {
        0.5: '半星',
        1: '一星',
        1.5: '一星半',
        2: '二星',
        2.5: '二星半',
        3: '三星',
        3.5: '三星半',
        4: '四星',
        4.5: '四星半',
        5: '五星'
    }
});
