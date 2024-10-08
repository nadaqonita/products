<?php

namespace App\DataTables\Helper;

use Illuminate\Support\Str;

class Script
{
    public static function make($type = ['submit', 'delete', 'show', 'export', 'chain-dropdown'], $config = ['url', 'param', 'btnClass', 'title', 'text', 'okBtn', 'noBtn', 'formId', 'modalId', 'btnId'])
    {
        $content = '';

        if ($type === 'delete') {
            $content .= self::deleteScript($config['btnClass'], $config['title'], $config['text'], $config['okBtn'] ?? 'Iya', $config['noBtn'] ?? 'Tidak', $config['url'], $config['param']);
        }

        if ($type === 'submit') {
            $content .= self::_submit($config['formId'], $config['url'], $config['modalId']);
        }

        if ($type === 'show') {

            $content .= self::_show($config['btnClass'], $config['url'], $config['param'], $config['formId']);
        }

        if ($type === 'export') {
            $content .= self::_export($config['formId'], $config['modalId'], $config['btnId'], $config['dateId']);
        }

        if ($type === 'chain-dropdown') {
            $content .= self::_chain($config['selectId'], $config['url'], $config['param'], $config['foemId'], $config['targetId']);
        }
        
        return $content;
    }

    private static function deleteScript($btnClass, $title, $text, $confirmButton, $cancelButton, $url, $param)
    {
        return '
        $(`body`).on(`click`,`.' . $btnClass . '`,function(){

            const id = $(this).data(`id`)

                Notiflix.Confirm.show(
                `' . $title . '`,
                `' . $text . '`,
                `' . $confirmButton . '`,
                `' . $cancelButton . '`,
                function okCb() {
                    $.ajax({
                        url:`' . $url . '`,
                        headers:{
                            "X-CSRF-TOKEN": "' . csrf_token() . '"
                        },
                        xhrFields: { withCredentials: true },
                        data:{
                            ' . $param . ':id
                        },
                        type:`POST`,
                        dataType:`JSON`,
                        beforeSend:(()=>{
                            // $(`#base-table`).DataTable().ajax.reload()
                            Notiflix.Loading.standard();
                        })
                    }).done((res)=>{
                        Notiflix.Loading.remove();
                        Notiflix.Notify.success(res.message)
                        $(`#base-table`).DataTable().ajax.reload()
                    }).fail((xhr)=>{
                        let res = xhr.responseJSON
                        Notiflix.Loading.remove();
                        Notiflix.Notify.failure(res.message)
                    })
                },
                function cancelCb() {

                }); //notiflix
            }) //onclick
        ';
    }

    protected static function _submit($formId, $url, $modalId)
    {
        return '
            modal = document.getElementById(`' . $modalId . '`)
            modal.addEventListener(`hidden.bs.modal`, function (event) {
                  $(`#' . $formId . '`).trigger(`reset`)
            })
            $(`body`).on(`submit`,`#' . $formId . '`,function (e)
            {
                e.preventDefault()
                let form = $(`#' . $formId . '`)[0]
                let inputForm = new FormData(form)

                $.ajax({
                    url:`' . $url . '`,
                    headers:{
                         "X-CSRF-TOKEN": "' . csrf_token() . '"
                    },
                    xhrFields: { withCredentials: true },
                    data:inputForm,
                    processData: false,
                    contentType: false,
                    type:`POST`,
                    dataType:`JSON`,
                    beforeSend:(()=>{
                        Notiflix.Loading.standard();
                    })
                }).done((res)=>{
                        Notiflix.Loading.remove();
                        Notiflix.Notify.success(res.message)
                        $(`#' . $modalId . '`).modal("hide")
                        $(`#base-table`).DataTable().ajax.reload()
                        $(`#' . $formId . '`).trigger(`reset`)
                }).fail((xhr)=>{
                    let res = xhr.responseJSON
                    Notiflix.Loading.remove();
                    Notiflix.Notify.failure(res.message)
                })

            })
        ';
    }

    protected static function _show($btnClass, $url, $param, $formId)
    {
        return '
            $(`body`).on(`click`,`.' . $btnClass . '`,function (e)
            {
                e.preventDefault()

                let id = $(this).data("id");

                $.ajax({
                    url:`' . $url . '`,
                    headers:{
                        "X-CSRF-TOKEN": "' . csrf_token() . '"
                    },
                    xhrFields: { withCredentials: true },
                    data:{
                        ' . $param . ':id
                    },
                    type:`POST`,
                    dataType:`JSON`,
                    beforeSend:(()=>{
                        Notiflix.Loading.standard();
                    })
                }).done((res)=>{
                        Notiflix.Loading.remove();
                        $.map(res.data,(v,i)=>{
                            $(`#' . $formId . ' #${i}`).val(v)
                        })
                }).fail((xhr)=>{
                    let res = xhr.responseJSON
                    Notiflix.Loading.remove();
                    Notiflix.Notify.failure(res.message)
                })

            })';
    }

    protected static function _export($formId, $modalId, $btnId, $dateId)
    {
        return '

          let flatpickr =  $(`#' . $modalId . ' #' . $dateId . '`).flatpickr({
                inline:true,
                mode:`range`,
                altInput:true,
                altInputClass:`d-none`,
            })


            $(`body`).on(`click`,`#' . $btnId . '`,function(){
                //  $(`#' . $formId . '`).trigger(`reset`)
                 flatpickr.clear()
            })
            $(`body`).on(`submit`,`#' . $formId . '`,function (e)
            {

                Notiflix.Loading.standard();
                $(`#' . $modalId . '`).modal("hide")
                Notiflix.Loading.remove();
            })

        ';
    }

    protected static function _chain($selectId, $url, $param, $formId, $targetId)
    {
        return '

            $(`body`).on(`change`,`#' . $selectId . '`,function(){
                let id = $(this).val();

                $.ajax({
                    url:`' . $url . '`,
                    headers:{
                         "X-CSRF-TOKEN": "' . csrf_token() . '"
                    },
                    xhrFields: { withCredentials: true },
                    data:{
                        ' . $param . ':id
                    },
                    type:`POST`,
                    dataType:`JSON`,
                    beforeSend:(()=>{
                        // Notiflix.Loading.standard();
                    })
                }).done((res)=>{
                        // Notiflix.Loading.remove();

                        $.map(res.data,(v,i)=>{
                            $(`#' . $formId . ' #' . $targetId . '`).append(`<option value="${v.value}">${v.text}</option>`)

                        })
                }).fail((xhr)=>{
                    let res = xhr.responseJSON
                    // Notiflix.Loading.remove();
                    Notiflix.Notify.failure(res.message)
                })

            })


        ';
    }
}
