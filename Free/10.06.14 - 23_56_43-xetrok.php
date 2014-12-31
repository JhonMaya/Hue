<?php exit() ?>--by xetrok 203.35.135.165
function k(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

_G.BolAuthLoad = function(str) 
assert(load(_ENV[k({66,97,115,101,54,52,68,101,99,111,100,101})](str), nil, "bt", _ENV))()
end
