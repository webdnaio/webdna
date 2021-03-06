var reportApp = {
    clickDetails: function () {
        $('a.details').on('click', function (ev) {

            var $target = $(ev.target).parents('tr'),
                itemId = $target.attr('data-item-id'),
                $detailsRow = $('.detailsRow');

            $('td.h-border').removeClass('h-border');

            if ($target.next('.detailsRow:visible').size() > 0) {
                $target.next('.detailsRow').hide();
                $(window).scrollTop($target.top);
                return false;
            }

            $target.find('td:first').addClass('h-border');

            var $currentIconClass = reportApp.spinner($(ev.currentTarget));

            $.get(Routing.generate('user_workbench_report_details_xhr', {type: 'html'}), {itemId: itemId}, function (data) {
                $target.after(data);
            }).success(function () {
                $(ev.currentTarget).find('i').attr('class', $currentIconClass);
                $(window).scrollTop($target.top);
            });
            return false;
        });
    },
    classify: function () {
        var $domainGroupsForm = $('#domainGroupsForm'),
            $clasifyMenu = $('div.classify-menu');

        $clasifyMenu.on('hidden.bs.dropdown', function () {
            $domainGroupsForm.find('input[name="grouped_website_id"]').val('');
        });

        $clasifyMenu.on('click', 'a.classifyWebsiteMenuItem', function (ev) {
            var $input = $(ev.currentTarget).find('input');

            ev.stopPropagation();

            if ($input.prop('checked') === true) {
                $domainGroupsForm.find('input[name="grouped_website_id"]').val($input.val());
            } else {
                $domainGroupsForm.find('input[name="grouped_website_id"]').val('');
            }

            if ($(ev.target).is('input')) {
                return true;
            }

            $input.click();
        });

        $clasifyMenu.on('click', 'a.classify', function (ev) {
            var $currentTarget = $(ev.currentTarget);

            if (
                $domainGroupsForm.find('input[name="grouped_website_id"]').val() !== ''
            ) {
                $domainGroupsForm.find('input[name="class"]').val($currentTarget.attr('data-rating'));
                $domainGroupsForm.submit();
                return false;
            }

            var $targetTr = $(ev.target).parents('tr'),
                itemId = $targetTr.attr('data-item-id'),
                $pageAlert = $('#pageAlert_' + itemId),
                $label = $pageAlert.find('span:first'),
                $icon = $pageAlert.find('i:first');

            $icon.attr('class', 'fa fa-circle-o-notch fa-spin');

            $.post(Routing.generate('user_workbench_classify_item_xhr'),
                {
                    'itemId': itemId,
                    'class': $currentTarget.attr('data-rating')
                })
                .done(function (data) {
                    $pageAlert.attr('class', 'label label-' + data.cssLabel + ' dropdown')
                        .parents('div.btn-group.open').removeClass('open');
                    $label.text(data.message);
                    $icon.attr('class', data.cssIcon);
                });
            return false;
        });
    },
    classifyWebsite: function () {
        $('a.classifyWebsite').on('click', function (ev) {
            var $domainGroupsForm = $('#domainGroupsForm'),
                dataRating = $(ev.currentTarget).attr('data-rating');
            if (dataRating > 0) {
                $domainGroupsForm.find('input[name=class]').val(dataRating);
            } else {
                $domainGroupsForm.find('input[name=revert]').val(1);
            }
            $domainGroupsForm.submit();
        });
    },
    getSelectedPagesIds: function() {
        var pageIds = [];
        $.each($('.page-item-input'), function(idx, element) {
            pageIds.push(($(element).attr('data-page-id')));
        });

        return pageIds;
    },
    getPagesIdsFromView: function() {
        var $tr = $('#report_table tr'),
            pageIds = [];
        $.each($tr, function (idx, element) {
            if (typeof $(element).attr('data-page-id') !== 'undefined') {
                pageIds.push(($(element).attr('data-page-id')));
            }
        });

        return pageIds;
    },
    markAsReviewed: function (el, websiteId, analysisProcessId, ids, reviewValue) {
        if (el !== null) {
            var $el = $(el);
            $el.find('i').attr('class', 'fa fa-circle-o-notch fa-spin');
        }

        $.post(Routing.generate('user_workbench_report_mask_as_reviewed', {
                analysisProcess: analysisProcessId
            }),
            {page_ids: JSON.stringify(ids)})
            .done(function () {
                document.location.href = Routing.generate('user_workbench_report_table', {
                    website: websiteId,
                    analysisProcess: analysisProcessId
                });
            })
            .fail(function () {
                if ($el !== undefined) {
                    $el.find('i').attr('class', 'fa fa-exclamation-triangle');
                }
            });
    },
    handleCriteriaForm: function () {
        var $criteriaForm = $('#criteriaForm'),
            $input = $('.report-tabs input'),
            $filters = $('#filters');

        $criteriaForm.find('.nav.nav-tabs').on('click', 'a', function (ev) {
            $(ev.target).find('input').attr('checked', true);
            $criteriaForm.submit();
        });

        $criteriaForm.find('.nav.nav-tabs').on('click', 'label', function (ev) {
            $(ev.target).previous('input[type=radio]').attr('checked', true);
            $criteriaForm.submit();
        });

        $filters.on('click', 'button[type=submit]', function () {
            $criteriaForm.submit();
        });

        $('.resetFilters').on('click', function (ev) {
            ev.stopPropagation();
            $criteriaForm.find('input[name="criteria[reset]"]').attr('disabled', false).val('1');
            $criteriaForm.submit();
        });

        // iCheck inputs
        $input.iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass: 'iradio_flat-green'
        });

        $input.on('ifChecked', function (ev) {
            document.location.href = $(ev.currentTarget).parents('a').attr('href');
        });

        var $filtersBtnGroup = $('#filters-btn-group');

        $filtersBtnGroup.on('show.bs.dropdown', function () {
            return false;
        });

        $filtersBtnGroup.on('hide.bs.dropdown', function () {
            return false;
        });

        $filtersBtnGroup.on('click', 'button:first-child', function () {
            $('#filters').toggleClass('hide');
            $filtersBtnGroup.toggleClass('open');
        });
    },
    loadWebsiteGroups: function ($buttonObj, analysisProcesId, reportType) {
        var $topWebsitesListHead = $('.top-websites-list-head'),
            $icon = $topWebsitesListHead.find('i.spinner'),
            $criteriaForm = $("#criteriaForm"),
            criteriaWebsiteSelector = '.setCriteriaWebsite';

        if ($buttonObj !== null) {
            if ($buttonObj.attr('data-clicked') === undefined) {
                $buttonObj.attr('data-clicked', 'true');

                $('.top-websites-list').on('click', criteriaWebsiteSelector, function () {
                    $criteriaForm.find("input[name=criteria\\[website\\]]").val($(this).attr('data-website-id'));
                    $criteriaForm.submit();
                });

            } else {
                return false;
            }
        }

        $icon.attr('class', 'fa fa-circle-o-notch fa-spin');

        $.getJSON(Routing.generate('user_workbench_website_groups_xhr', {
            analysisProcess: analysisProcesId
        }), function (data) {
            var items = data.pageItemAnalyzes, $hookElement = $topWebsitesListHead.next();

            for (var idx in items) {
                $hookElement.before('<li><a class="setCriteriaWebsite" href="#" data-website-id="' + items[idx].website_id + '">'
                + '<i class="fa fa-circle-o"></i> ' + items[idx].name + '</a></li>');
            }
            $hookElement.remove();

            $('.top-websites-count').text(' (' + data.totalItemCount + ')');

        }).done(function () {
            $icon.attr('class', '');
        });
    },
    clearWebsiteGroupsFilter: function () {
        var $criteriaForm = $("#criteriaForm");
        $criteriaForm.find('input[name=criteria\\[website\\]]').val('reset');
        $criteriaForm.submit();
    },
    spinner: function ($currentTarget) {
        var $currentIcon = $currentTarget.find('i');
        var $currentIconClasses = $currentIcon.attr('class');
        $currentIcon.attr('class', 'fa fa-circle-o-notch fa-spin');
        return $currentIconClasses;
    },
    selectItem: function (analysisProcessId, itemId, markSelected) {
        reportApp.selectItemsSubmit(analysisProcessId, [itemId], markSelected);
    },
    selectItems: function (analysisProcessId, itemsIds, markSelected) {
        reportApp.selectItemsSubmit(analysisProcessId, itemsIds, markSelected);
    },
    selectItemsSubmit: function (analysisProcessId, itemsIds, markSelected) {
        $.post(Routing.generate('user_workbench_report_set_items_selected', {analysisProcess: analysisProcessId}),
            {
                'item_ids': JSON.stringify(itemsIds),
                'mark_selected': markSelected === true ? 'select' : 'unselect'
            });
    },
    toggleSelectAllItems: function (analysisProcessId, el) {
        var $el = $(el),
            $pageItemInput = $('.page-item-input'),
            checked = $el.prop('checked'),
            itemsIds = [];

        $pageItemInput.prop('checked', checked);

        $.each($pageItemInput, function (idx, item) {
            itemsIds.push($(item).val());
        });

        reportApp.selectItems(analysisProcessId, itemsIds, checked);
    },
    handleSelectItem: function (analysisProcessId) {
        $('.page-item-input').on('click', function (ev) {
            reportApp.selectItem(analysisProcessId, $(ev.target).val(), $(ev.target).prop('checked'));
        });
    }
};

