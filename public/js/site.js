$(function() {
    var page = 1;
    $("#loadmore").click(function(){
        $(this).html('加载中……');
        $.ajax({
            type: 'GET',
            url: "/reviews?type="+$(this).attr("datatype")+"&page="+page,
            dataType: 'html',
            success: function(data) {
                $("#loadmore").html('加载更多');
                if($.trim(data)==''){
                    $("#loadmore").remove();
                }
                else {
                    $('#indexReviews').append('<div id="page'+page+'">'+data+'</div>');
                    $('#page'+page+' .review-content').readmore({
                        collapsedHeight: 125,
                        moreLink: '<a href="#" class="morelink nobkg">显示全部</a>',
                        lessLink: '<a href="#" class="morelink nobkg">收起</a>'
                    });
                    page++;
                }
            },
            error: function(xhr, type) {
                $("#loadmore").html('加载更多');
                alert(xhr);
                alert(type);
            }
        });
    });
    $("#loadmore").click();
});

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

$('#itemModal').on('show.bs.modal', function (event) {
    var modal = $(this);
    var item = $(event.relatedTarget).data('item');
    modal.find("input[name='no']").prop('value', item.no);
    modal.find("[name='review']").val(item.review);
    modal.find("form").prop('action', $(event.relatedTarget).data('action'));
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

$(function () {
    $("#selectAll").click(function () {
        $(":checkbox").prop("checked", true);
    });
    $("#unSelect").click(function () {
        $(":checkbox").prop("checked", false);
    });
});

$(function () {
    $('.introduction').readmore({
        collapsedHeight: 100,
        moreLink: '<a href="#" class="nobkg">显示全部</a>',
        lessLink: '<a href="#" class="nobkg">收起</a>'
    });
    $('.review-content').readmore({
        collapsedHeight: 125,
        moreLink: '<a href="#" class="morelink nobkg">显示全部</a>',
        lessLink: '<a href="#" class="morelink nobkg">收起</a>'
    });
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
