namespace cpp xxfilter
namespace java xxfilter
namespace php xxfilter

struct XXFilterSpamTextReq {
      1: binary msg_text
        2: i64 msg_id
          3: binary msg_type
}

struct XXFilterSpamTextRes {
      1: bool spam_flag
        2: double spam_ratio
}

service XXFilterSpamTextService{
      XXFilterSpamTextRes xxfilter_spam_text(1: XXFilterSpamTextReq request)
}
