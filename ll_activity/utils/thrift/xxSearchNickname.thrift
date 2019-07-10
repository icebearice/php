namespace cpp xxsearch
namespace java xxsearch
namespace perl xxsearch
namespace php xxsearch
namespace haxe xxsearch

struct XXSearchNicknameRes {
  1: bool success
  2: list<binary> nickname_list
  3: list<binary> uid_list
}

service XXSearchNicknameService {
  XXSearchNicknameRes xxsearch(1: binary query, 2: i32 index_num, 3: i32 total_count)
}
