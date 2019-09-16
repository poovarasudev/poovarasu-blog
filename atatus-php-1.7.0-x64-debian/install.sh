#!/bin/bash

atatus_script_version="1.7.0"
atatus_log_file="atatus_install_log.txt"

ATATUS_API_KEY=${ATATUS_API_KEY:-""}
ATATUS_LICENSE_KEY=${ATATUS_LICENSE_KEY:-""}
ATATUS_APP_NAME=${ATATUS_APP_NAME:-""}

realpath() {
    canonicalize_path "$(resolve_symlinks "$1")"
}

resolve_symlinks() {
    _resolve_symlinks "$1"
}

_resolve_symlinks() {
    _assert_no_path_cycles "$@" || return

    local dir_context path
    path=$(readlink -- "$1")
    if [ $? -eq 0 ]; then
        dir_context=$(dirname -- "$1")
        _resolve_symlinks "$(_prepend_dir_context_if_necessary "$dir_context" "$path")" "$@"
    else
        printf '%s\n' "$1"
    fi
}

_prepend_dir_context_if_necessary() {
    if [ "$1" = . ]; then
        printf '%s\n' "$2"
    else
        _prepend_path_if_relative "$1" "$2"
    fi
}

_prepend_path_if_relative() {
    case "$2" in
        /* ) printf '%s\n' "$2" ;;
         * ) printf '%s\n' "$1/$2" ;;
    esac
}

_assert_no_path_cycles() {
    local target path

    target=$1
    shift

    for path in "$@"; do
        if [ "$path" = "$target" ]; then
            return 1
        fi
    done
}

canonicalize_path() {
    if [ -d "$1" ]; then
        _canonicalize_dir_path "$1"
    else
        _canonicalize_file_path "$1"
    fi
}

_canonicalize_dir_path() {
    (cd "$1" 2>/dev/null && pwd -P)
}

_canonicalize_file_path() {
    local dir file
    dir=$(dirname -- "$1")
    file=$(basename -- "$1")
    (cd "$dir" 2>/dev/null && printf '%s/%s\n' "$(pwd -P)" "$file")
}

# Optionally, you may also want to include:

### readlink emulation ###

readlink() {
    if _has_command readlink; then
        _system_readlink "$@"
    else
        _emulated_readlink "$@"
    fi
}

_has_command() {
    hash -- "$1" 2>/dev/null
}

_system_readlink() {
    command readlink "$@"
}

_emulated_readlink() {
    if [ "$1" = -- ]; then
        shift
    fi

    _gnu_stat_readlink "$@" || _bsd_stat_readlink "$@"
}

_gnu_stat_readlink() {
    local output
    output=$(stat -c %N -- "$1" 2>/dev/null) &&

    printf '%s\n' "$output" |
        sed "s/^‘[^’]*’ -> ‘\(.*\)’/\1/
             s/^'[^']*' -> '\(.*\)'/\1/"
    # FIXME: handle newlines
}

_bsd_stat_readlink() {
    stat -f %Y -- "$1" 2>/dev/null
}

log_exec() {
    echo "Command: $@" >> ${atatus_log_file}
    "$@" >> ${atatus_log_file} 2>&1
    local code=$?
    if [ $code -ne 0 ]; then
        echo "Failed: ${code}" >> ${atatus_log_file}
        echo "Failed: "$@"" >&2
        echo "" >&2
        echo "Please check log file \""${atatus_log_file}"\" for details and contact us at hello@atatus.com" >&2
        exit 1
    else
        echo "Success" >> ${atatus_log_file}
    fi
}

log_file() {
    echo "$@" >> ${atatus_log_file}
}

log_info() {
    echo "$@" >&2
    echo "$@" >> ${atatus_log_file}
}

log_fatal() {
    echo "Error: $@" >&2
    echo "Error: $@" >> ${atatus_log_file}
    echo "Please check log file \""${atatus_log_file}"\" for details and contact us at hello@atatus.com" >&2
    exit 1
}

detect_php_path() {
    local path
    if [ -n "${ATATUS_PHP_PATH}" -a -x "${ATATUS_PHP_PATH}/php" ]; then
        path=${ATATUS_PHP_PATH}
    else
        local which_php
        which_php=`which php 2>/dev/null`
        [ -n "${which_php}" ] && path=`dirname ${which_php}`
    fi
    echo $path
}

print_php_code() {
    local php_output
    local cmd
    cmd="<?php print ${1}; ?>"
    php_output=`echo ${cmd} | ${php_exc} -d display_errors=Off -d display_startup_errors=Off -d error_reporting=0 -q 2> /dev/null`
    echo ${php_output}
}

get_php_info() {
    php_version=$(print_php_code 'phpversion()')

    local ext_dir
    ext_dir=$(print_php_code ini_get\("extension_dir"\))
    if [ -n "${ext_dir}" -a ! -d "${ext_dir}" ]; then
        if ! mkdir -p "${ext_dir}" 2>/dev/null; then
            log_fatal "PHP extensions dir '${ext_dir}' creation failed"
        fi
    fi

    if [ -n "${ext_dir}" -a ! -w "${ext_dir}" ]; then
        log_fatal "PHP extensions dir '${ext_dir}' is not writable. Check if you are running it as root."
    fi

    php_ext_dir=$(realpath ${ext_dir})

    local config_scan_dir
    config_scan_dir=$(print_php_code 'PHP_CONFIG_FILE_SCAN_DIR')
    php_config_scan_dir=$(realpath ${config_scan_dir})

    if [ -n "${php_config_scan_dir}" -a ! -d "${php_config_scan_dir}" ]; then
        if ! mkdir -p "${php_config_scan_dir}" 2>/dev/null; then
            log_fatal "PHP config scan dir '${php_config_scan_dir}' creation failed"
        fi
    fi

    if [ -n "${php_config_scan_dir}" -a ! -w "${php_config_scan_dir}" ]; then
        log_fatal "PHP config scan dir '${php_config_scan_dir}' is not writable. Check if you are running it as root."
    fi

    local config_file_path
    config_file_path=$(print_php_code 'PHP_CONFIG_FILE_PATH')
    php_config_file_path=$(realpath ${config_file_path})

    local php_info
    php_info=/tmp/.atatus_php_info
    "${php_exc}" -i > ${php_info} 2>&1
    if grep "Thread Safety" ${php_info} | grep 'enabled' > /dev/null 2>&1; then
        php_thread_safety="yes"
    elif grep "Thread Safety" ${php_info} | grep 'disabled' > /dev/null 2>&1; then
        php_thread_safety="no"
    else
        log_fatal "Thread Safety not found in ${php_info}"
    fi
}

check_php_info() {
    if [ -z ${php_exc} ]; then
        log_fatal "PHP Executable not found"
    fi

    if [ -z ${php_version} ]; then
        log_fatal "PHP Version not found"
    fi

    if [ -z ${php_ext_dir} ]; then
        log_fatal "PHP Extension Directory not found"
    fi

    if [ -z ${php_config_scan_dir} ]; then
        log_fatal "PHP Config Scan Directory not found"
    fi

    # if [ -z ${php_config_file_path} ]; then
    #     log_fatal "PHP Config File Path not found"
    # fi
}

print_php_info() {
    log_file ""
    log_file "PHP executable       : ${php_exc}"
    log_file "PHP version          : ${php_version}"
    log_file "PHP thread safety    : ${php_thread_safety}"
    log_file "PHP extension_dir    : ${php_ext_dir}"
    log_file "PHP config_scan dir  : ${php_config_scan_dir}"
    log_file "PHP config_file path : ${php_config_file_path}"
    log_file ""
}

detect_os() {
    case $(uname) in
        Linux)
            os_type="linux"
            os_bin_install_path="/usr/bin"
            os_share_install_path="/usr/share/doc"
            os_lib_install_path="/usr/lib"
            os_var_log_install_path="/var/log"
            os_php_locations_prefix="/etc/php"
            ;;
        Darwin)
            os_type="darwin"
            os_bin_install_path="/usr/local/bin"
            os_share_install_path="/usr/local/share/doc"
            os_lib_install_path="/usr/local/lib"
            os_var_log_install_path="/usr/local/var/log"
            os_php_locations_prefix="/usr/local/etc/php"
            ;;
        *)
            log_fatal "Unsupported platform: " $(uname)
            ;;
    esac

    log_file "OS Detected: ${os_type}"
}


common() {

    php_path=$(detect_php_path)
    if [ -z ${php_path} ]; then
        log_fatal "php executable path not found"
    fi

    php_exc="${php_path}/php"
    if [ -z "${php_exc}" -o ! -x "${php_exc}" ] ; then
        log_fatal "PHP executable not found at ${php_path}"
    fi

    get_php_info
    print_php_info
    check_php_info

    if [ "${os_type}" == "darwin" ]; then
        if [ "${php_thread_safety}" == "yes" ]; then
            log_fatal "ZTS is not supported in Mac OS. Please contact us at hello@atatus.com"
        fi
    fi

    if [ "${php_thread_safety}" == "yes" ]; then
        php_atatus_thread_safety_ext_suffix="_zts"
    else
        php_atatus_thread_safety_ext_suffix=""
    fi

    case "${php_version}" in
        5.4.*) php_atatus_ext_suffix="5.4" ;;
        5.5.*) php_atatus_ext_suffix="5.5" ;;
        5.6.*) php_atatus_ext_suffix="5.6" ;;
        7.0.*) php_atatus_ext_suffix="7.0" ;;
        7.1.*) php_atatus_ext_suffix="7.1" ;;
        7.2.*) php_atatus_ext_suffix="7.2" ;;
        7.3.*) php_atatus_ext_suffix="7.3" ;;
        *) log_fatal "Unsupported version ${php_version}" ;;
    esac

    case "${php_version}" in
        5.*.*) os_php_locations="${os_php_locations_prefix}5* ${os_php_locations_prefix}/5*" ;;
        7.*.*) os_php_locations="${os_php_locations_prefix}/7.*" ;;
        *) log_fatal "Unsupported version ${php_version}" ;;
    esac

    #log_file "atatus library.so: ${php_atatus_ext_suffix}"

    atatus_install_dir=`dirname $0`
    log_file "Install Directory: ${atatus_install_dir}"
}

uninstall_agent() {
    common

    atatus_dest_agent_file="${php_ext_dir}/atatus.so"
    log_exec rm -f ${atatus_dest_agent_file}

    # log_exec rm -f "${os_bin_install_path}/atatus-php-collector"
    # log_exec rm -rf "${os_lib_install_path}/atatus-php"
    # log_exec rm -rf "${os_share_install_path}/atatus-php"
    # log_exec rm -f "${os_bin_install_path}/atatus-php-installer"
    # log_exec rm -f "${os_var_log_install_path}/atatus"

    if [ -e ${php_config_scan_dir}/atatus.ini ]; then
        log_exec rm -f "${php_config_scan_dir}/atatus.ini"

        for php_loc in ${os_php_locations}; do
            if [ "${php_config_scan_dir}" == "${php_loc}/cli/conf.d" ]; then
                if [ -d "${php_loc}/apache2/conf.d" ]; then
                    if [ -e "${php_loc}/apache2/conf.d/atatus.ini" ]; then
                        log_exec rm -f "${php_config_scan_dir}/atatus.ini"
                    fi
                fi

                if [ -d "${php_loc}/fpm/conf.d" ]; then
                    if [ -e "${php_loc}/fpm/conf.d/atatus.ini" ]; then
                        log_exec rm -f "${php_config_scan_dir}/atatus.ini"
                    fi
                fi
            fi
        done
    fi

    log_info "Uninstalled Atatus PHP Agent Successfully"
}

install_agent() {
    common

    # Verify that ATATUS_LICENSE_KEY environment variable was provided
    # case ${ATATUS_LICENSE_KEY} in
    #   (*[![:blank:]]*) ;;
    #   (*)  log_fatal "Environment variable ATATUS_LICENSE_KEY has not been set. Please set ATATUS_LICENSE_KEY environment variable."
    # esac

    if [ "${os_type}" == "linux" ]; then
        log_exec cp -f "${atatus_install_dir}/etc/logrotate.d/atatus-php-collector" "/etc/logrotate.d/"
        log_exec cp -f "${atatus_install_dir}/etc/logrotate.d/atatus-php-agent" "/etc/logrotate.d/"
    fi
    log_exec cp -f "${atatus_install_dir}/usr/bin/atatus-php-collector" "${os_bin_install_path}/"
    log_exec mkdir -p "${os_lib_install_path}/atatus-php/"
    log_exec cp -Rf "${atatus_install_dir}/usr/lib/atatus-php/"* "${os_lib_install_path}/atatus-php/"

    local atatus_src_agent_file="${os_lib_install_path}/atatus-php/x86_64/atatus_php${php_atatus_thread_safety_ext_suffix}_${php_atatus_ext_suffix}.so"
    local atatus_dest_agent_file="${php_ext_dir}/atatus.so"
    log_exec rm -f ${atatus_dest_agent_file}
    log_exec ln -sf ${atatus_src_agent_file} ${atatus_dest_agent_file}
    log_exec mkdir -p "${os_share_install_path}/atatus-php/"
    log_exec cp -Rf "${atatus_install_dir}/usr/share/doc/atatus-php/"*       "${os_share_install_path}/atatus-php/"

    if [ "${os_type}" == "darwin" ]; then
        log_file "Replacing atatus-php-installer"
        cat "${atatus_install_dir}/usr/bin/atatus-php-installer" | \
                sed -e "s/\/usr\/lib/\/usr\/local\/lib/" \
                    > "${os_bin_install_path}/atatus-php-installer"
    else
        log_exec cp -f "${atatus_install_dir}/usr/bin/atatus-php-installer" "${os_bin_install_path}/"
    fi
    log_exec chmod +x "${os_bin_install_path}/atatus-php-installer"

    if [ ! -e ${php_config_scan_dir}/atatus.ini ]; then
        if [ "${os_type}" == "darwin" ]; then
          log_file "Replacing atatus.ini template"
          cat "${atatus_install_dir}/usr/lib/atatus-php/atatus.ini.template" | \
                      sed -e "s/\/usr\/bin/\/usr\/local\/bin/" \
                          -e "s/\/var\/log/\/usr\/local\/var\/log/" \
                          -e "s/\/var\/run/\/usr\/local\/var\/run/" \
                          > "${php_config_scan_dir}/atatus.ini"
          log_exec chmod 644 "${php_config_scan_dir}/atatus.ini"
        else
            log_exec cp "${atatus_install_dir}/usr/lib/atatus-php/atatus.ini.template" "${php_config_scan_dir}/atatus.ini"
            log_exec chmod 644 "${php_config_scan_dir}/atatus.ini"
        fi

        for php_loc in ${os_php_locations}; do
            if [ "${php_config_scan_dir}" == "${php_loc}/cli/conf.d" ]; then
                log_file "${php_loc} matches with config_scan dir"
                if [ -d "${php_loc}/apache2/conf.d" ]; then
                    if [ ! -e "${php_loc}/apache2/conf.d/atatus.ini" ]; then
                        log_exec ln -sf "${php_config_scan_dir}/atatus.ini" "${php_loc}/apache2/conf.d/atatus.ini"
                    else
                        log_file "Skipping apache2 link creation, as the file already exists"
                    fi
                fi

                if [ -d "${php_loc}/fpm/conf.d" ]; then
                    if [ ! -e "${php_loc}/fpm/conf.d/atatus.ini" ]; then
                        log_exec ln -sf "${php_config_scan_dir}/atatus.ini" "${php_loc}/fpm/conf.d/atatus.ini"
                    else
                        log_file "Skipping fpm link creation, as the file already exists"
                    fi
                fi
            fi
        done
    else
        log_file "Skipping atatus.ini link creation, as the file already exists"
    fi

    if [ ! -d ${os_var_log_install_path} ]; then
        log_fatal "${os_var_log_install_path} not present"
    fi

    if [ ! -d "${os_var_log_install_path}/atatus" ]; then
        log_exec mkdir -p "${os_var_log_install_path}/atatus"
        log_exec chmod 777 "${os_var_log_install_path}/atatus"
    fi

    if [ -d "${os_var_log_install_path}/atatus" ]; then
        log_exec chmod 777 "${os_var_log_install_path}/atatus"
        if [ ! -e "${os_var_log_install_path}/atatus/agent.log" ]; then
            log_exec touch "${os_var_log_install_path}/atatus/agent.log"
        fi
        log_exec chmod 777 "${os_var_log_install_path}/atatus/agent.log"
        if [ ! -e "${os_var_log_install_path}/atatus/collector.log" ]; then
            log_exec touch "${os_var_log_install_path}/atatus/collector.log"
        fi
        log_exec chmod 777 "${os_var_log_install_path}/atatus/collector.log"
        if [ ! -e "${os_var_log_install_path}/atatus/debug.txt" ]; then
            log_exec touch "${os_var_log_install_path}/atatus/debug.txt"
        fi
        log_exec chmod 777 "${os_var_log_install_path}/atatus/debug.txt"
    fi


    if [ $ATATUS_API_KEY ]; then
        log_file "Setting your api key to the agent configuration: ${os_atatus_config_yml}"
        log_exec sh -c "sed -i -e 's/atatus\.api_key = .*/atatus\.api_key = \"$ATATUS_API_KEY\"/' ${php_config_scan_dir}/atatus.ini"
    else
        if [ $ATATUS_LICENSE_KEY ]; then
            log_file "Setting your license key to the agent configuration: ${os_atatus_config_yml}"
            log_exec sh -c "sed -i -e 's/atatus\.license_key = .*/atatus\.license_key = \"$ATATUS_LICENSE_KEY\"/' ${php_config_scan_dir}/atatus.ini"
        else
            log_info "Note: Environment variable ATATUS_LICENSE_KEY has not been set. Please set the license key in atatus.ini file manually."
        fi
    fi

    if [ "$ATATUS_APP_NAME" ]; then
        log_file "Setting your app name to the agent configuration: ${os_atatus_config_yml}"
        log_exec sh -c "sed -i -e 's/atatus\.app_name = .*/atatus\.app_name = \"$ATATUS_APP_NAME\"/' ${php_config_scan_dir}/atatus.ini"
    fi

    log_info "Installed Atatus PHP Agent Successfully"
}

action=
case "$1" in
    "install") action=$1 ;;
    "uninstall") action=$1 ;;
    *) action="install" ;;
esac

timestamp=`date -u`

log_info ""
log_info "Atatus PHP Agent installation script v${atatus_script_version}"
log_info "========================================================="
log_info ""
log_info "Action: ${action}: ${timestamp}"
log_info ""

detect_os

[ "${action}" = "install" ] && install_agent
[ "${action}" = "uninstall" ] && uninstall_agent

exit 0