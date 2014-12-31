<?php exit() ?>--by ragequit 174.53.87.155

local Names = {
 "MrSithSquirrel",
 "ragequit",
 "iRes",
}

local f = false
for _, Name in pairs(Names) do
 if GetUser() == Name then
  f = true
 end
end

if not f then return end
if f then PrintChat("hey we passed the auth check") end
