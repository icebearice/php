namespace go GPUser
namespace php GPUser
namespace py GPUser
//namespace java GPUser
//namespace cpp GPUser

struct GpUserBase {
  1: i32 uid
  2: string uname
  3: string usalt
  4: string upwd
  5: string uex
  6: string uphone
  7: string uemail
  8: string unickname
}

struct GpUserEx {
  1: i32 productid
  2: i32 cid
  3: i16 usex
  4: string usfz
  5: i32 uregtime
  6: string urealname
  7: string ubbspwd
  8: string uico
  9: i16 ustatus
  10: string unickname //moved to GpUserBase
  11: list<i32> ufid
  12: string usignature
  13: i16 platform
  14: binary user_info //都是空的
  15: string imei
  16: string address
  17: string qq
  18: string wechat
  19: string birthday
}

struct GpUser {
    1: GpUserBase base
    2: GpUserEx ex
}

struct GetUserReq {
    1: i32 uid
    2: string u //uname or uphone
    3: string unickname
}

struct GetUserRes {
    1: bool success
    2: GpUser user
}

struct CheckLoginStateReq {
    1: i32 product_id
    2: i32 appid
    3: string login_key
    4: i32 uin
    5: string uuid
    6: string remote_addr
}

struct CheckLoginStateRes {
    1: bool success
    2: i32 code
}

struct UpdateUserReq {
    1: GpUser user // some columns allow to be updated ( uphone, uemail, usex, usfz, urealname, address, qq, wechat, birthday )
}

struct UpdateUserRes {
    1: bool success
    2: i32 code
}

struct GetAppGameUinReq {
    1: i32 appid
    2: i32 uid
    3: i32 cid
}

struct GetAppGameUinRes {
    1: bool success
    2: string game_uin
}

struct GetUidFromGameUinReq {
    1: i32 appid
    2: string game_uin
}

struct GetUidFromGameUinRes {
    1: bool success
    2: i32 uid
}

struct GetUidAllAppidAndGameUinReq {
    1: i32 uid
}

struct AppidAndGameUin {
    1: i32 uid
    2: i32 appid
    3: string game_uin
    4: i32 addtime
    5: i32 cid
    6: i32 ucid
}
struct GetUidAllAppidAndGameUinRes {
    1: bool success
    2: list<AppidAndGameUin> AppidAndGameUinArr
}

struct AddUserReq {
    1: GpUser user
    2: i32 appid
    3: string ip //为了日志记录
}

struct AddUserRes {
    1: bool success
    2: i32 uid
    3: string game_uin
}

service GPUser {
    GetUserRes GetUser( 1: GetUserReq req ),
    CheckLoginStateRes CheckLoginState( 1: CheckLoginStateReq req ),
    UpdateUserRes UpdateUser( 1: UpdateUserReq req ),
    GetAppGameUinRes GetAppGameUin( 1: GetAppGameUinReq req ),
    GetUidFromGameUinRes GetUidFromGameUin( 1: GetUidFromGameUinReq req ),
    GetUidAllAppidAndGameUinRes GetUidAllAppidAndGameUin( 1: GetUidAllAppidAndGameUinReq req ),
    AddUserRes AddUser( 1: AddUserReq req )
}
