namespace cpp rpcdatareport
namespace java rpcdatareport
namespace php rpcdatareport

################################
struct GPGameSdkLoginLogReq {
  1: binary uid
  2: binary appid
  3: binary time
  4: binary user_info
}

struct GPGameSdkLoginLogRes {
  1: bool status
}

service GPGameSdkLoginLogService {
  GPGameSdkLoginLogRes gp_gamesdk_loginlog_datareport(1: GPGameSdkLoginLogReq request)
}

################################
struct GPLoginLogReq {
  1: binary uid
  2: binary time
  3: binary ip
  4: binary product_id
}

struct GPLoginLogRes {
  1: bool status
}

service GPLoginLogService {
  GPLoginLogRes gp_loginlog_datareport(1: GPLoginLogReq request) 
}

################################
struct GPGameInstallListReq {
  1: binary pb_str 
}

struct GPGameInstallListRes {
  1: bool status
}

service GPGameInstallListService {
  GPGameInstallListRes gp_game_install_list_datareport(1: GPGameInstallListReq request)
}

################################
struct GPGameThirdTransferLoginLogReq {
  1: binary json_str 
}

struct GPGameThirdTransferLoginLogRes {
  1: bool status
}

service GPGameThirdTransferLoginLogService {
  GPGameThirdTransferLoginLogRes gp_game_third_transfer_loginlog_datareport(1: GPGameThirdTransferLoginLogReq request)
}

################################
struct GPH5GameLoginLogReq {
  1: binary uid
  2: binary time
  3: binary ip
  4: binary product_id
  5: binary sourceid 
  6: binary appid
  7: binary platform
  8: binary channel_id
  9: binary uuid
  10: binary device_id
}

struct GPH5GameLoginLogRes {
  1: bool status
}

service GPH5GameLoginLogService {
  GPH5GameLoginLogRes gp_h5_game_loginlog_datareport(1: GPH5GameLoginLogReq request) 
}