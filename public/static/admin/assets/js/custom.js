$( document ).ready(function() {

    let duplicatable = $('[data-feature="duplicate"]');
    duplicatable.each(function( index ) {
        let self = $(this);
        let max = self.attr('data-feature-max');
        let list = self.find('[data-feature-type="list"]');
        let duplicates = list.children('[data-feature-list="duplicatable"]');
        let actions = self.find('[data-feature-type="actions"]');
        if (duplicates.length > 1){
            actions.children('[data-feature-action="pop"]').prop('disabled', false);
        }
        if (duplicates.length < max){
            actions.children('[data-feature-action="add"]').prop('disabled', false);
        }
        if (duplicates.length > 0){
            duplicates.slice(1).find('[data-feature-list-type="remove"]').prop('disabled', false);
        }
    });

    $(document).on('click', '[data-feature-action="add"]', function () {
        let self = $(this);
        let duplicatable = self.closest('[data-feature="duplicate"]');
        let list = duplicatable.find('[data-feature-type="list"]');
        let duplicatableList = list.find('[data-feature-list="duplicatable"]');
        let first = duplicatableList.first();
        let last = duplicatableList.last();

        let toDuplicate = first.clone();
        let target = toDuplicate.find('[data-feature-list-type="target"]');
        target.val('');
        target.removeAttr('id');
        target.attr('placeholder', target.attr('placeholder') + ' #' + (parseInt(duplicatableList.length) + 1));
        toDuplicate.find('[data-feature-list-type="remove"]').prop('disabled', false);
        last.after(toDuplicate);
        let duplicatableObj = duplicatable.closest('[data-feature="duplicate"]');
        let max = parseInt(duplicatableObj.attr('data-feature-max'));

        if (duplicatableList.length == max - 1){
            self.prop('disabled', true);
        }

        duplicatableObj.find('[data-feature-action="pop"]').prop('disabled', false);


    });

    $(document).on('click', '[data-feature-action="pop"]', function () {
        let self = $(this);
        let duplicatable = self.closest('[data-feature="duplicate"]');
        let list = duplicatable.find('[data-feature-type="list"]');
        let duplicatableList = list.find('[data-feature-list="duplicatable"]');
        let last = duplicatableList.last();

        if (duplicatableList.length - 1 == 1){
            self.prop('disabled', true);
        }
        last.remove();
        duplicatable.find('[data-feature-action="add"]').prop('disabled', false);
    });

    $(document).on('click', '[data-feature-list-type="remove"]', function () {
        let self = $(this);
        let duplicatable = self.closest('[data-feature="duplicate"]');
        let duplicatableObj = duplicatable.closest('[data-feature="duplicate"]');
        let max = duplicatable.attr('data-feature-max');
        let list = duplicatable.find('[data-feature-type="list"]');
        let duplicatableList = list.find('[data-feature-list="duplicatable"]');

        self.closest('[data-feature-list="duplicatable"]').remove();

        let actions = duplicatableObj.find('[data-feature-type="actions"]');
        if (duplicatableList.length < max){
            actions.find('[data-feature-action="add"]').prop('disabled', false);
        }
        if (duplicatableList.length == 1){
            actions.find('[data-feature-action="pop"]').prop('disabled', true);
        }
    });


    $(document).on('click', '[data-action="toBottom"]', function () {
        let element = $(this).closest('[data-feature="swap"]');
        let id = element.attr('data-id');
        let url = element.parent().attr('data-swap-url');
        let next = element.next();
        postAjaxRequest(url,
            {
                id: id,
                action: 'toBottom'
            },
            function (data) {

                next.find('[data-action="toBottom"]').prop('disabled', false);
                element.find('[data-action="toTop"]').prop('disabled', false);

                if (element.prev().length == 0){
                    next.find('[data-action="toTop"]').prop('disabled', true);
                }
                if (next.next().length == 0){
                    element.find('[data-action="toBottom"]').prop('disabled', true);
                }

                next.after(element);
            },
            function (data) {
                return false;
            },
            false
        );
    });

    $(document).on('click', '[data-action="toTop"]', function () {
        let element = $(this).closest('[data-feature="swap"]');
        let id = element.attr('data-id');
        let url = element.parent().attr('data-swap-url');
        let prev = element.prev();

        postAjaxRequest(url,
            {
                id: id,
                action: 'toTop'
            },
            function (data) {
                element.find('[data-action="toBottom"]').prop('disabled', false);
                prev.find('[data-action="toTop"]').prop('disabled', false);

                if (prev.prev().length == 0){
                    element.find('[data-action="toTop"]').prop('disabled', true);
                }

                if (element.next().length == 0){
                    prev.find('[data-action="toBottom"]').prop('disabled', true);
                }

                prev.before(element);
            },
            function (data) {
                return false;
            },
            false
        );
    });
});

String.prototype.toHHMMSS = function () {
    var sec_num = parseInt(this, 10); // don't forget the second param
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours == 0) {
        hours = null;
    } else if (hours   < 10) {
        hours   = "0"+hours;
    }
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}

    return ((hours != null) ? hours + ':' : '') + minutes + ':' + seconds;
}




function round(value, decimals) {
    if (value == parseInt(value)) return parseInt(value);
    return Number(Math.round(value+'e'+decimals)+'e-'+decimals);
}





