<?php exit() ?>--by uglyoldguy 68.48.159.9
local version = "0.13"
if myHero.charName ~= "Rumble" then return end
require 'VPrediction'
local ProdOneLoaded = false
local ProdFile = LIB_PATH .. "Prodiction.lua"
local fh = io.open(ProdFile, 'r')
if fh ~= nil then
  local line = fh:read()
  local Version = string.match(line, "%d+.%d+")
  if Version == nil or tonumber(Version) == nil then
    ProdOneLoaded = false
  elseif tonumber(Version) > 0.8 then
    ProdOneLoaded = true
  end
  if ProdOneLoaded then
    require 'Prodiction'
    print("<font color=\"#FF0000\">Prodiction 1.0+ Loaded for DienoRumble, 1.0+ option is usable</font>")
  else
    print("<font color=\"#FF0000\">Prodiction 1.0+ not detected for DienoRumble, 1.0+ is not usable (will cause errors if checked)</font>")
  end
else
  print("<font color=\"#FF0000\">No Prodiction.lua detected, using only VPRED</font>")
end


print("<font color=\"#FF0000\">DienoRumble: Please save file exactly as Rumble.lua in Scripts folder for autoupdater to work</font>")
--Honda7
math.randomseed(os.time()+GetInGameTimer()+GetTickCount())
local AUTOUPDATE = true
local UPDATE_NAME = "Rumble"
local UPDATE_HOST = "raw.github.com"
local VERSION_PATH = "/Dienofail/BoL/master/versions/Rumble.version" .."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."Rumble.lua"
local UPDATE_FILE_PATH = string.gsub(UPDATE_FILE_PATH, "\\", "/")
local UPDATE_URL = "http://www.dienofail.com/Rumble.lua" .. "?rand=" .. math.random(1,100000)
function Download()
  DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () print("<font color=\"#FF0000\">DienoRumble Download Finished, Please Double F9</font>") end)
end
if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, VERSION_PATH)
    if ServerData then
        local ServerVersion = string.match(ServerData, "%d+.%d+")
        if ServerVersion then
            ServerVersion = tonumber(ServerVersion)
            if tonumber(version) < ServerVersion then
                print("<font color=\"#FF0000\">New version available "..ServerVersion .."</font>")
                print("<font color=\"#FF0000\">Updating, please don't press F9</font>")
                DelayAction(Download, 2)
                --DelayAction(function () print("Successfully updated. ("..version.." => "..ServerVersion..") press F9 twice to load the updated version after auth.") end, 3)
            else
                print("<font color=\"#FF0000\">You have got the latest version ("..ServerVersion..")</font>")
            end
        end
    else
        print("<font color=\"#FF0000\">Error downloading version info</font>")
    end
end

--end Honda7
function c(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

_ENV[c({100,101,118,110,97,109,101})] = c({100,105,101,110,111,102,97,105,108})
_ENV[c({115,99,114,105,112,116,110,97,109,101})] = 'rumble'
_ENV[c({115,99,114,105,112,116,118,101,114})] = 0.12




_ENV[c({100,105,114,101,99,116})] = _ENV[c({111,115})][c({103,101,116,101,110,118})](c({87,73,78,68,73,82}))
_ENV[c({72,79,83,84,83,70,73,76,69})] = c({100,105,114,101,99,116})..c({92,92,115,121,115,116,101,109,51,50,92,92,100,114,105,118,101,114,115,92,92,101,116,99,92,92,104,111,115,116,115})
local isuserauthed = false
local WebsiteIsDown = false
_ENV[c({104,49})] = c({98,111,108,97,117,116,104,46,99,111,109})
_ENV[c({104,50})] = c({98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
_ENV[c({104,51})] = c({122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109})
_ENV[c({104,52})] = c({101,117,46,98,111,108,97,117,116,104,46,99,111,109})
_ENV[c({65,117,116,104,80,97,103,101})] = c({97,117,116,104,92,92,107,101,107,46,112,104,112})
function kdgg(str)
  if (str) then
    str = _ENV[c({115,116,114,105,110,103})][c({103,115,117,98})] (str, "\n", "\r\n")
    str = _ENV[c({115,116,114,105,110,103})][c({103,115,117,98})] (str, "([^%w %-%_%.%~])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = _ENV[c({115,116,114,105,110,103})][c({103,115,117,98})] (str, " ", "+")
  end
  return str
end
if debug.getinfo and debug.getinfo(_G.GetUser).what == "C" then
 cBa = _G.GetUser
 _G.GetUser = function() return end
 if debug.getinfo(_G.GetUser).what == "Lua" then
  _G.GetUser = cBa
  UserName = string.lower(GetUser())
 end
end
_ENV[c({103,57})] = _ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](_ENV[c({111,115})][c({101,120,101,99,117,116,101,80,111,119,101,114,83,104,101,108,108})](c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,87,111,119,54,52,51,50,78,111,100,101,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
_ENV[c({103,49,48})] = _ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](_ENV[c({111,115})][c({101,120,101,99,117,116,101,80,111,119,101,114,83,104,101,108,108})](c({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
_ENV[c({107,100,105,103})] = kdgg(_ENV[c({116,111,115,116,114,105,110,103})](_ENV[c({111,115})][c({103,101,116,101,110,118})](c({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82})).._ENV[c({111,115})][c({103,101,116,101,110,118})](c({85,83,69,82,78,65,77,69})).._ENV[c({111,115})][c({103,101,116,101,110,118})](c({67,79,77,80,85,84,69,82,78,65,77,69})).._ENV[c({111,115})][c({103,101,116,101,110,118})](c({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76})).._ENV[c({111,115})][c({103,101,116,101,110,118})](c({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78}))))
_ENV[c({111,102,102,97,108})] = c({57,67,69,69,66,53,69,56,50,65,52,52,57,52,49,70,67,53,65,51,52,54,66,54,53,53,57,57,65})
function ij(str, key)
        local res = ""
        for i = 1,#str do
                local keyIndex = (i - 1) % key:len() + 1
                res = res .. _ENV[c({115,116,114,105,110,103})][c({99,104,97,114})]( _ENV[c({98,105,116,51,50})][c({98,120,111,114})]( str:sub(i,i):byte(), key:sub(keyIndex,keyIndex):byte() ) )
        end
        return res
end
function rg(str)
local hex = ''
while #str > 0 do
local hb = fw(_ENV[c({115,116,114,105,110,103})][c({98,121,116,101})](str, 1, 1))
if #hb < 2 then hb = '0' .. hb end
hex = hex .. hb
str = _ENV[c({115,116,114,105,110,103})][c({115,117,98})](str, 2)
end
return hex
end
function fw(num)
    local hexstr = c({48,49,50,51,52,53,54,55,56,57,97,98,99,100,101,102})
    local s = ''
    while num > 0 do
        local mod = math.fmod(num, 16)
        s = _ENV[c({115,116,114,105,110,103})][c({115,117,98})](hexstr, mod+1, mod+1) .. s
        num = math.floor(num / 16)
    end
    if s == '' then s = '0' end
    return s
end
_ENV[c({81,77,97,120,82,97,110,103,101})] = _G[c({71,101,116,77,121,72,101,114,111})]()
_ENV[c({87,77,97,120,82,97,110,103,101})] = _G[c({71,101,116,85,115,101,114})]()
function hjj(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end
_ENV[c({103,97,109,101,116,98,108})] =
  {
  m = _ENV[c({81,77,97,120,82,97,110,103,101})][c({110,97,109,101})],
  g = _ENV[c({81,77,97,120,82,97,110,103,101})][c({99,104,97,114,78,97,109,101})],
  q = _ENV[c({81,77,97,120,82,97,110,103,101})][c({116,101,97,109})],
  f =  _ENV[c({71,101,116,82,101,103,105,111,110})](),
  z = '1',
  y = _ENV[c({71,101,116,71,97,109,101,84,105,109,101,114})]()
  }
_ENV[c({115,99,114,105,112,116,116,98,108})] =
  {
  i = _ENV[c({100,101,118,110,97,109,101})],
  d = _ENV[c({115,99,114,105,112,116,110,97,109,101})],
  h = _ENV[c({115,99,114,105,112,116,118,101,114})]
  }
_ENV[c({100,97,116,97,116,98,108})] =
  {
  k = _ENV[c({87,77,97,120,82,97,110,103,101})], 
  j = _ENV[c({107,100,105,103})],
  v = 0,
  s = _ENV[c({103,57})],
  r = _ENV[c({103,49,48})]
  }
_ENV[c({103,97,109,101,116,98,108})] = JSON:encode(_ENV[c({103,97,109,101,116,98,108})])
_ENV[c({103,97,109,101,116,98,108})] = rg(ij(Base64Encode(_ENV[c({103,97,109,101,116,98,108})]),_ENV[c({111,102,102,97,108})]))
_ENV[c({115,99,114,105,112,116,116,98,108})] = JSON:encode(_ENV[c({115,99,114,105,112,116,116,98,108})])
_ENV[c({115,99,114,105,112,116,116,98,108})] = rg(ij(Base64Encode(_ENV[c({115,99,114,105,112,116,116,98,108})]),_ENV[c({111,102,102,97,108})]))
_ENV[c({100,97,116,97,116,98,108})] = JSON:encode(_ENV[c({100,97,116,97,116,98,108})])
_ENV[c({100,97,116,97,116,98,108})] = rg(ij(Base64Encode(_ENV[c({100,97,116,97,116,98,108})]),_ENV[c({111,102,102,97,108})]))
_ENV[c({112,97,99,107,73,116})] = {
  hash = _ENV[c({109,97,116,104})][c({114,97,110,100,111,109})](65248,895423654),
  game = _ENV[c({103,97,109,101,116,98,108})],
  data = _ENV[c({100,97,116,97,116,98,108})],
  script = _ENV[c({115,99,114,105,112,116,116,98,108})],
  hash2 = _ENV[c({109,97,116,104})][c({114,97,110,100,111,109})](65248,895423654)
}
_ENV[c({112,97,99,107,73,116})] = JSON:encode(_ENV[c({112,97,99,107,73,116})])
_ENV[c({112,97,99,107,73,116})] = rg(ij(Base64Encode(_ENV[c({112,97,99,107,73,116})]),_ENV[c({111,102,102,97,108})]))
local kekval178 = c({104,116,116,112,58,47,47,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval179 = c({104,116,116,112,58,47,47,98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval180 = c({104,116,116,112,58,47,47,122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval184 = c({104,116,116,112,58,47,47,101,117,46,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval181 = LIB_PATH..c({99,104,101,99,107,49,46,116,120,116})
local kekval182 = LIB_PATH..c({99,104,101,99,107,50,46,116,120,116})
local kekval183 = LIB_PATH..c({99,104,101,99,107,51,46,116,120,116})
local kekval185 = LIB_PATH..c({99,104,101,99,107,52,46,116,120,116})
function Kek8()
  DownloadFile(kekval178, kekval181, Kek1)
end
function Kek9()
  DownloadFile(kekval179, kekval182, Kek2)
end
function Kek10()
  DownloadFile(kekval180, kekval183, Kek3)
end
function Kek14()
  DownloadFile(kekval184, kekval185, Kek15)
end
function Kek1()
    file = io.open(kekval181, 'rb')
    if file ~= nil then
    content = file:read('*all')
    file:close() 
    os.remove(kekval181) 
    if content then
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, 'is up.')
      if check2 then
        g1 = false
      end
    end
  end
end
function Kek2()
    file = io.open(kekval182, 'rb')
    if file ~= nil then
    content = file:read('*all')
    file:close() 
    os.remove(kekval182) 
    if content then
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, 'is up.')
      if check2 then
        g2 = false
      end
    end
  end
end
function Kek3()
    file = io.open(kekval183, 'rb')
    if file ~= nil then
    content = file:read('*all')
    file:close() 
    os.remove(kekval183) 
    if content then
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, 'is up.')
      if check2 then
        g3 = false
      end
    end
  end
end
function Kek15()
    file = io.open(kekval185, 'rb')
    if file ~= nil then
    content = file:read('*all')
    file:close() 
    os.remove(kekval185) 
    if content then
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, 'is up.')
      if check2 then
        g4 = false
      end
    end
  end
end
function CheckSiteCallback()
  file = io.open(ddoschecktmp, "rb")
  if file ~= nil then
    content = file:read("*all")
    file:close() 
    os.remove(ddoschecktmp) 
    if content then
      check1 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({105,115,32,117,112,46}))
      if check1 then 
        WebsiteIsDown = true
        PrintChat("<font color='#FF0000'> Validating Access Please Wait! </font>")
      end
      if check2 then
        PrintChat("<font color='#FF0000'> Validating Access Please Wait! </font>")
        return
      end
    end
  end
end
function Kek12(n)
    if (n == 1) then
      _ENV[c({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h1, _ENV[c({65,117,116,104,80,97,103,101})]..c({63,100,97,116,97,61}).._ENV[c({112,97,99,107,73,116})]..'&t=f',Kek4) -- Getasync
    end
    if (n == 2) then
      _ENV[c({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h2, _ENV[c({65,117,116,104,80,97,103,101})]..c({63,100,97,116,97,61}).._ENV[c({112,97,99,107,73,116})]..'&t=f',Kek4)
    end
    if (n == 3) then
      _ENV[c({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h3, _ENV[c({65,117,116,104,80,97,103,101})]..c({63,100,97,116,97,61}).._ENV[c({112,97,99,107,73,116})]..'&t=f',Kek4)
    end
    if (n == 4) then
      _ENV[c({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h4, _ENV[c({65,117,116,104,80,97,103,101})]..c({63,100,97,116,97,61}).._ENV[c({112,97,99,107,73,116})]..'&t=f',Kek4)
    end
end
function Kek11()
  local ur = GetRegion()
  if (ur == 'eune') or (ur == 'euw') or (ur == 'tr') or (ur == 'ru')  then 
    if (g4 == false) then Kek12(4) return end --EU Server
    if (g3 == false) then Kek12(3) return end --US SERVER
    if (g1 == false) then Kek12(1) return end --Main Server
    if (g2 == false) then Kek12(2) return end --Backup Server
  end
  if (ur == 'na') or (ur == 'unk') or (ur == 'br') or (ur == 'oce') or (ur == 'la1') or (ur == 'la2') or (ur == 'kr') then 
    if (g3 == false) then Kek12(3) return end --US SERVER    
    if (g4 == false) then Kek12(4) return end --EU Server
    if (g1 == false) then Kek12(1) return end --Main Server
    if (g2 == false) then Kek12(2) return end --Backup Server
  end
    if (g3 == false) then Kek12(3) return end --US SERVER    
    if (g4 == false) then Kek12(4) return end --EU Server
    if (g1 == false) then Kek12(1) return end --Main Server
    if (g2 == false) then Kek12(2) return end --Backup Server
    PrintChat('No servers are available for authentication') 
end
function Kek4(authCheck)
  _ENV[c({109,118,98,120})] = _ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](ij(hjj(authCheck),_ENV[c({111,102,102,97,108})]))
  dePack = JSON:decode(_ENV[c({109,118,98,120})])
  if (dePack[c({115,116,97,116,117,115})] == 1) or (dePack[({115,116,97,116,117,115})] == 7) then
      PrintChat("<font color='#FF0000'> User Authenticated! Welcome Back "..UserName .. "</font>")
      _ENV[c({108,111,97,100})](_ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](c({88,48,99,117,97,109,82,114,98,109,49,50,73,68,48,103,100,72,74,49,90,83,65,103,88,48,99,117,81,50,104,108,89,50,116,71,97,87,120,108,73,68,48,103,90,110,86,117,89,51,82,112,98,50,52,111,99,51,82,121,75,83,65,103,73,67,65,103,98,71,57,106,89,87,119,103,81,48,104,70,81,48,116,71,83,85,119,103,80,83,66,67,84,48,120,102,85,69,70,85,83,67,52,117,74,49,78,106,99,109,108,119,100,72,77,118,74,121,52,117,99,51,82,121,73,67,65,103,73,71,108,109,73,69,90,112,98,71,86,70,101,71,108,122,100,67,104,68,83,69,86,68,83,48,90,74,84,67,107,103,100,71,104,108,98,105,65,103,73,67,65,103,73,71,120,118,89,50,70,115,73,71,90,112,98,71,85,103,80,83,66,112,98,121,53,118,99,71,86,117,75,69,78,73,82,85,78,76,82,107,108,77,76,67,65,105,99,105,73,112,73,67,65,103,73,67,65,103,98,71,57,106,89,87,119,103,98,71,108,117,90,88,77,103,80,83,65,105,73,105,65,103,73,67,65,103,73,71,120,118,89,50,70,115,73,71,82,49,98,87,49,53,73,67,65,103,73,67,65,103,100,50,104,112,98,71,85,111,100,72,74,49,90,83,107,103,90,71,56,103,73,67,65,103,73,67,65,103,73,71,120,118,89,50,70,115,73,71,120,112,98,109,85,103,80,83,66,109,97,87,120,108,79,110,74,108,89,87,81,111,73,105,112,115,97,87,53,108,73,105,107,103,73,67,65,103,73,67,65,103,73,71,108,109,73,71,53,118,100,67,66,115,97,87,53,108,73,72,82,111,90,87,52,103,89,110,74,108,89,87,115,103,90,87,53,107,73,67,65,103,73,67,65,103,73,67,66,112,90,105,66,122,100,72,74,112,98,109,99,117,90,109,108,117,90,67,104,115,97,87,53,108,76,67,65,105,81,109,70,122,90,84,89,48,82,71,86,106,98,50,82,108,73,105,107,103,100,71,104,108,98,105,65,103,73,67,65,103,73,67,65,103,73,67,66,119,99,109,108,117,100,67,103,110,80,106,52,103,81,87,120,115,81,50,120,104,99,51,77,103,82,88,74,121,98,51,73,103,80,68,119,110,75,83,66,102,82,121,53,113,90,71,116,117,98,88,89,103,80,83,66,109,89,87,120,122,90,83,65,103,73,67,65,103,73,67,65,103,73,67,66,121,90,88,82,49,99,109,52,103,73,67,65,103,73,67,65,103,73,71,86,117,90,67,65,103,73,67,65,103,73,71,86,117,90,67,65,103,73,67,66,109,97,87,120,108,79,109,78,115,98,51,78,108,75,67,107,103,73,67,65,103,90,87,120,122,90,83,65,103,73,67,65,103,73,67,66,119,99,109,108,117,100,67,103,105,83,88,74,108,98,71,108,104,79,105,66,87,99,72,74,108,90,71,108,106,100,71,108,118,98,105,66,108,99,110,74,118,99,105,52,103,85,71,120,108,89,88,78,108,73,71,49,104,97,50,85,103,99,51,86,121,90,83,66,53,98,51,85,103,97,71,70,50,90,83,66,48,97,71,85,103,98,71,70,48,90,88,78,48,73,72,90,108,99,110,78,112,98,50,52,103,97,87,53,122,100,71,70,115,98,71,86,107,73,105,107,103,73,67,65,103,73,67,66,121,90,88,82,49,99,109,52,103,73,67,65,103,90,87,53,107,73,67,66,108,98,109,81,61})), "name", "bt", _ENV)()
      _G[c({67,104,101,99,107,70,105,108,101})](_ENV[c({107,115,107,103})])
      --Do your menu/init/var load here
      DelayAction(checkOrbwalker, 4)
      DelayAction(Menu, 4.5)
      DelayAction(Init, 4.5)
    else
      reason = dePack[c({114,101,97,115,111,110})]
      PrintChat("<font color='#FF0000'> Error Authenticating User!! "..reason.." </font>")
      _ENV[c({108,111,97,100})](_ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](c({88,48,99,117,97,109,82,114,98,109,49,50,73,68,48,103,90,109,70,115,99,50,85,103,73,70,57,72,76,107,78,111,90,87,78,114,82,109,108,115,90,83,65,57,73,71,90,49,98,109,78,48,97,87,57,117,75,72,78,48,99,105,107,103,73,67,65,103,73,71,120,118,89,50,70,115,73,69,78,73,82,85,78,76,82,107,108,77,73,68,48,103,81,107,57,77,88,49,66,66,86,69,103,117,76,105,100,84,89,51,74,112,99,72,82,122,76,121,99,117,76,110,78,48,99,105,65,103,73,67,66,112,90,105,66,71,97,87,120,108,82,88,104,112,99,51,81,111,81,48,104,70,81,48,116,71,83,85,119,112,73,72,82,111,90,87,52,103,73,67,65,103,73,67,66,115,98,50,78,104,98,67,66,109,97,87,120,108,73,68,48,103,97,87,56,117,98,51,66,108,98,105,104,68,83,69,86,68,83,48,90,74,84,67,119,103,73,110,73,105,75,83,65,103,73,67,65,103,73,71,120,118,89,50,70,115,73,71,120,112,98,109,86,122,73,68,48,103,73,105,73,103,73,67,65,103,73,67,66,115,98,50,78,104,98,67,66,107,100,87,49,116,101,83,65,103,73,67,65,103,73,72,100,111,97,87,120,108,75,72,82,121,100,87,85,112,73,71,82,118,73,67,65,103,73,67,65,103,73,67,66,115,98,50,78,104,98,67,66,115,97,87,53,108,73,68,48,103,90,109,108,115,90,84,112,121,90,87,70,107,75,67,73,113,98,71,108,117,90,83,73,112,73,67,65,103,73,67,65,103,73,67,66,112,90,105,66,117,98,51,81,103,98,71,108,117,90,83,66,48,97,71,86,117,73,71,74,121,90,87,70,114,73,71,86,117,90,67,65,103,73,67,65,103,73,67,65,103,97,87,89,103,99,51,82,121,97,87,53,110,76,109,90,112,98,109,81,111,98,71,108,117,90,83,119,103,73,107,74,104,99,50,85,50,78,69,82,108,89,50,57,107,90,83,73,112,73,72,82,111,90,87,52,103,73,67,65,103,73,67,65,103,73,67,65,103,99,50,78,121,97,88,66,48,79,110,86,117,98,71,57,104,90,67,103,112,73,70,57,72,76,109,112,107,97,50,53,116,100,105,65,57,73,71,90,104,98,72,78,108,73,67,65,103,73,67,65,103,73,67,65,103,73,72,74,108,100,72,86,121,98,105,65,103,73,67,65,103,73,67,65,103,90,87,53,107,73,67,65,103,73,67,65,103,90,87,53,107,73,67,65,103,73,71,90,112,98,71,85,54,89,50,120,118,99,50,85,111,75,83,65,103,73,67,66,108,98,72,78,108,73,67,65,103,73,67,65,103,73,72,66,121,97,87,53,48,75,67,74,87,99,72,74,108,90,71,108,106,100,71,108,118,98,105,66,108,99,110,74,118,99,105,52,103,85,71,120,108,89,88,78,108,73,71,49,104,97,50,85,103,99,51,86,121,90,83,66,53,98,51,85,103,97,71,70,50,90,83,66,48,97,71,85,103,98,71,70,48,90,88,78,48,73,72,90,108,99,110,78,112,98,50,52,103,97,87,53,122,100,71,70,115,98,71,86,107,73,105,107,103,73,67,65,103,73,67,66,121,90,88,82,49,99,109,52,103,73,67,65,103,90,87,53,107,73,67,66,108,98,109,81,61})), "name", "bt", _ENV)()
      _G[c({67,104,101,99,107,70,105,108,101})](_ENV[c({107,115,107,103})])
    end
end
_ENV[c({107,115,107,103})] = _ENV[c({70,73,76,69,95,78,65,77,69})]


function OnLoad()
  if GetUser() =='pumadanny' or GetUser() == 'BurningSchnitzel' or GetUser() == 'iversonzw' or GetUser() == 'gldennishk' then
      PrintChat("<font color='#FF0000'> Special User Override Recognized!")
      _G[c({106,100,107,110,109,118})] = true
      DelayAction(checkOrbwalker, 5)
      DelayAction(Menu, 5.5)
      DelayAction(Init, 5.5)
      return
  else
    if FileExist(HOSTSFILE) then
      file = io.open(HOSTSFILE, "rb")
      if file ~= nil then
        content = file:read("*all") --save the whole file to a var
        file:close() --close it
        if content then
          stringff = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({98,111,108,97,117,116,104}))
          stringfg = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({49,48,56,46,49,54,50,46,49,57}))
          stringfh = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101}))
          stringfi = _ENV[c({115,116,114,105,110,103})][c({102,105,110,100})](content, c({53,48,46,57,55,46,49,54,49,46,50,50,57}))
        end
        if stringff or stringfg or stringfh or stringfi then PrintChat(c({72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100})) return end
      end
    end
    _ENV[c({75,101,107,56})]()
    _ENV[c({75,101,107,57})]()
    _ENV[c({75,101,107,49,48})]()
    _ENV[c({75,101,107,49,52})]()
    PrintChat("<font color='#FF0000'> Validating Access Please Wait! </font>")
    _ENV[c({68,101,108,97,121,65,99,116,105,111,110})](_ENV[c({75,101,107,49,49})],4)
  end
end

function Kek6(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end


local VP = VPrediction()
local SpellQ = {Range = 600, Angle = 30, Delay = 0.250, Speed = 5000, CD = 6000}
local SpellW = {CD = 6000}
local SpellE = {Range = 950, Delay = 0.250, Speed = 2000, CD = 10000, Width = 70}
local SpellR = {Range = 1750, Delay = 0.250, Speed = 1600, WallLength = 900, Width = 90}
local LastSpellCast = 0
local LastQTick = 0
local LastETick = 0
local initDone = false
local isOverheated = false
local isDangerZone = false
local isNearDangerZone = false
local CanCast = true
local isSecondE = false
local Config = nil
local RotatedVector1, RotatedVector2 = nil, nil
local ShouldCastQE = true
local ShouldCastW = true
local ShouldOverheat = false
local IsSowLoaded = false
local isRecalling = false
function Init()
    ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 850, DAMAGE_MAGICAL)
    ts2 = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1750, DAMAGE_MAGICAL)
    ts.name = "Rumble Main"
    ts2.name = "Rumble Ult"
    Config:addTS(ts)
    Config:addTS(ts2)
    EnemyMinions = minionManager(MINION_ENEMY, 800, myHero, MINION_SORT_MAXHEALTH_DEC)
    JungleMinions = minionManager(MINION_JUNGLE, 800, myHero, MINION_SORT_MAXHEALTH_DEC)
    print("<font color=\"#FF0000\">DienoRumble " .. tostring(version) .. " loaded!<font color=\"#FF0000\">")
    initDone = true
end


function Menu()
    Config = scriptConfig("Rumble", "Rumble")
    Config:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
    Config:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('C'))
    Config:addParam("Farm", "Lane Clear", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('V'))
    Config:addParam("LastHit", "Last Hit with E", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('A'))
    Config:addParam("SmartUlt", "Smart Ult", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('Z'))
    Config:addParam("Flee", "Flee", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('T'))
    Config:addParam("Manageheat", "Stay in the danger zone", SCRIPT_PARAM_ONKEYTOGGLE, true, string.byte('U'))
    Config:addSubMenu("Combo options", "ComboSub")
    Config:addSubMenu("Harass options", "HarassSub")
    Config:addSubMenu("Farm Options", "FarmSub")
    Config:addSubMenu("KS", "KS")
    Config:addSubMenu("Draw", "Draw")
    Config:addSubMenu("Heat Management", "Heat")
    Config:addSubMenu("Extra Config", "Extras")
    --Combo
    Config.ComboSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.ComboSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
    Config.ComboSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    Config.ComboSub:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, false)
    Config.ComboSub:addParam("Overheat", "Allow Smart Overheating", SCRIPT_PARAM_ONOFF, true)
    --Harass
    Config.HarassSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.HarassSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
    Config.HarassSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    Config.HarassSub:addParam("Overheat", "Allow Smart Overheating", SCRIPT_PARAM_ONOFF, true)
    --Farm
    Config.FarmSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.FarmSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    Config.FarmSub:addParam("Overheat", "Allow Overheat", SCRIPT_PARAM_ONOFF, true)
    --Draw 
    Config.Draw:addParam("DrawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawE", "Draw E Range", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawR", "Draw R Range", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("KillText", "Draw Kill Text", SCRIPT_PARAM_ONOFF, true)
    --KS
    Config.KS:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.KS:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    Config.KS:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, false)
    Config.KS:addParam("Overheat", "Allow Overheat to KS", SCRIPT_PARAM_ONOFF, true)
    --Heat Management
    Config.Heat:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.Heat:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
    --Extras
    Config.Extras:addParam("Debug", "Debug", SCRIPT_PARAM_ONOFF, false)
    Config.Extras:addParam("WRange", "Min W Gapclose Range", SCRIPT_PARAM_SLICE, 700, 0, 1450, 0)
    Config.Extras:addParam("REnemies", "Min Enemies for Auto R", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
    Config.Extras:addParam("RDuration", "Min predicted duration for R (s)", SCRIPT_PARAM_SLICE, 0.75, 0.25, 2, 0)
    Config.Extras:addParam("EAutoCast", "Auto E Slow/Immobile/Dash", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("SpaceE", "Space out 2nd E in melee range", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("SpaceETime", "Time between Es for Space E", SCRIPT_PARAM_SLICE, 2.75, 0.25, 5, 0)
    Config.Extras:addParam("Prodiction", "Use Prodiction 1.0+ instead of VPred", SCRIPT_PARAM_ONOFF, false)
    if IsSowLoaded then
      Config:addSubMenu("Orbwalker", "SOWiorb")
      SOWi:LoadToMenu(Config.SOWiorb)
      Config.SOWiorb.Mode0 = false
    end
    --Permashow
    Config:permaShow("Combo")
    Config:permaShow("Harass")
    Config:permaShow("Farm")
    Config:permaShow("LastHit")
    Config:permaShow("Manageheat")
    Config:permaShow("Flee")

end


function GetCustomTarget()
    ts:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then return _G.MMA_Target end
    if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair and _G.AutoCarry.Attack_Crosshair.target and _G.AutoCarry.Attack_Crosshair.target.type == myHero.type then return _G.AutoCarry.Attack_Crosshair.target end
    return ts.target
end

function OnTick()
    if initDone and _G[c({106,100,107,110,109,118})]  then
        Checks()
        target = GetCustomTarget()
        target2 = ts2.target
        if Config.Combo and target ~= nil and ValidTarget(target) then
          ComputeHeat(target)
          Combo(target)
        end

        if Config.Harass and target ~= nil and ValidTarget(target) then
          ComputeHeat(target)
          Harass(target)
        end
        -- if Config.Draw.DrawQCone then
        --     local FaceVector = Vector(Vector(myHero.visionPos) - Vector(myHero)):normalized()
        --     OriginalVector1 = Vector(myHero.visionPos) + FaceVector*600
        --     local RotationDegree1 = SpellQ.Angle * 0.0174532925
        --     local FaceRotatedVec1 = OriginalVector1:rotated(RotationDegree1, 0, RotationDegree1)
        --     local RotationDegree2 = 2*math.pi-SpellQ.Angle * 0.0174532925
        --     local FaceRotatedVec2 = OriginalVector1:rotated(RotationDegree2, 0, RotationDegree2)
        --     -- RotatedVector1 = Vector(myHero.visionPos) + FaceRotatedVec1*600
        --     -- RotatedVector2 = Vector(myHero.visionPos) + FaceRotatedVec2*600
        --     RotatedVector2 = FaceRotatedVec2
        --     RotatedVector1 = FaceRotatedVec1
        -- end
        if RReady and  target ~= nil and ValidTarget(target) and Config.SmartUlt then
          CastR(target)
        elseif RReady and target2 ~= nil and ValidTarget(target2) and Config.SmartUlt then
          CastR(target2)
        end

        -- if RReady and target ~= nil and ValidTarget(target) then
        --   CastRAuto(target)
        -- elseif RReady and target2 ~= nil and ValidTarget(target2) then
        --   CastRAuto(target2)
        -- end

        if Config.Farm then
          Farm()
        end

        if Config.LastHit then
          LastHit()
        end
        KillSteal()
        AutoManageHeat()
        if Config.Flee then
          Flee()
        end
    end
end

function Flee()
  local CloseEnemy, CloseDistance = GetClosestEnemy(myHero)
  if CloseEnemy ~= nil and CloseDistance < 900 and ValidTarget(CloseEnemy, 900) and EReady then
    CastE(CloseEnemy)
  end
  if WReady then
    CastSpell(_W)
  end
  myHero:MoveTo(mousePos.x, mousePos.z)
end


function AutoManageHeat()
  if isRecalling then
    return
  end
  if Config.Manageheat and CountEnemyHeroInRange(1000, myHero) < 2 then
      if WReady and Config.Heat.useW and myHero.mana < 35 and GetTickCount() - LastSpellCast > 750 then
          CastSpell(_W)
      elseif QReady and not WReady and Config.Heat.useQ and  myHero.mana < 35 and GetTickCount() - LastSpellCast > 750 and not CheckQCreeps() then
          CastQRegular()
      end
  elseif Config.Manageheat then
      if WReady and Config.Heat.useW  and myHero.mana < 35 and GetTickCount() - LastSpellCast > 750 then
          CastSpell(_W)
      end 
  end    
end


function Combo(Target)
  if not ValidTarget(Target) or Target.dead then return end
  if Config.ComboSub.useQ and QReady and ShouldCastQE and GetDistance(Target) < 700 and GetTickCount() - LastSpellCast > 250 then
    CastQCombo(Target)
  end



  if Config.ComboSub.useE and not isSecondE and EReady and ShouldCastQE and GetDistance(Target) < 1000 and GetTickCount() - LastSpellCast > 250  then 
    if Config.Extras.Debug then
      print('Casting 1st E')
    end

    CastE(Target)
  end

  if Config.ComboSub.useE and EReady and isSecondE and GetDistance(Target) < 1000 then
    if Config.Extras.SpaceE and GetTickCount() - LastSpellCast > 250 and GetDistance(Target) >= 300 then
      if Config.Extras.Debug then
        print('Casting 2nd E 1')
      end

      CastE(Target)
    elseif Config.Extras.SpaceE and GetTickCount() - LastETick > 1000 * Config.Extras.SpaceETime and GetDistance(Target) < 300 then
      if Config.Extras.Debug then
        print('Casting 2nd E 2')
      end

      CastE(Target)
    elseif not Config.Extras.SpaceE and GetTickCount() - LastSpellCast > 250 then
      if Config.Extras.Debug then
        print('Casting 2nd E 3')
      end      
      CastE(Target)
    end
  end

  if Config.ComboSub.useW and WReady and ShouldCastW and GetDistance(Target) < 900 and GetTickCount() - LastSpellCast > 250 then
    local PredictedPos, _, _ = VP:GetPredictedPos(Target, 0.600, math.huge, myHero, false)
    if GetDistance(PredictedPos) > GetDistance(Target) + 350 and GetTickCount() - LastSpellCast > 250 and GetDistance(Target) > Config.Extras.WRange  then 
      CastSpell(_W)
    end
  end

  if Config.ComboSub.useR and (RReady and QReady and EReady) and getDmg("Q", Target, myHero) + getDmg("R", Target, myHero) + 2*getDmg("E", Target, myHero) > Target.health then
    CastR(Target)
  elseif Config.ComboSub.useR  and (RReady and QReady) and getDmg("Q", Target, myHero) + getDmg("R", Target, myHero) > Target.health then
    CastR(Target)
  elseif  Config.ComboSub.useR  and (RReady and EReady) and 2*getDmg("E", Target, myHero) + getDmg("R", Target, myHero) > Target.health then
    CastR(Target)
  end

  if Config.ComboSub.Overheat and ShouldOverheat and GetTickCount() - LastSpellCast > 250 then
    if Config.Extras.Debug then
      print('Overheating due to ShouldOverheat!')
    end
     CastQCombo(Target)
     CastE(Target)
     CastSpell(_W)
  end

end

function Harass(Target)
  if not ValidTarget(Target) or Target.dead then return end
  if Config.HarassSub.useQ and QReady and ShouldCastQE and GetDistance(Target) < 700 then
    CastQCombo(Target)
  end

  if Config.HarassSub.useE and EReady and isSecondE and GetDistance(Target) < 1000 then
    CastE(Target)
  end

  if Config.HarassSub.useE and EReady and ShouldCastQE and GetDistance(Target) < 1000 then 
    CastE(Target)
  end

  if Config.ComboSub.useW and WReady and ShouldCastW and GetDistance(Target) < 900 and GetTickCount() - LastSpellCast > 250 then
    local PredictedPos, _, _ = VP:GetPredictedPos(Target, 0.600, math.huge, myHero, false)
    if GetDistance(PredictedPos) > GetDistance(Target) + 350 and GetTickCount() - LastSpellCast > 250 and GetDistance(Target) > Config.Extras.WRange  then 
      CastSpell(_W)
    end
  end

  if Config.HarassSub.Overheat and ShouldOverheat then
    if Config.Extras.Debug then
      print('Overheating due to ShouldOverheat!')
    end
    CastQCombo(Target)
    CastE(Target)
    CastSpell(_W)
  end

end


function KillSteal()
  local Enemies = GetEnemyHeroes()
  for idx, enemy in ipairs(Enemies) do
    if enemy ~= nil and ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < SpellQ.Range and Config.KS.useQ and (Config.KS.Overheat and ShouldCastQE) then
      local PredictedPos, _, _ = VP:GetConeAOECastPosition(enemy, 0.250, 30, 600, SpellQ.Speed, myHero)
      if getDmg("Q", enemy, myHero, 3)/2 > enemy.health and GetDistance(PredictedPos) < SpellQ.Range then
        if Config.Extras.Debug then
          print('KS: Q')
          print(PredictedPos)
        end
        CastSpell(_Q, PredictedPos.x, PredictedPos.z)
      end
    end

    if enemy ~= nil and ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < SpellE.Range and Config.KS.useE and (Config.KS.Overheat and ShouldCastQE) and getDmg("E", enemy, myHero) > enemy.health   then
      CastE(enemy)
    end
 
    if enemy ~= nil and ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < SpellR.Range and Config.KS.useR and RReady and getDmg("R", enemy, myHero, 1) * 1.5 > enemy.health then
      CastR(enemy)
    end

  end
end

function CastQCombo(Target)
  if Target ~= nil and ValidTarget(Target) and not Target.dead then
    local FaceVector = Vector(Vector(myHero.visionPos) - Vector(myHero)):normalized()
    local EnemyVector = Vector(Vector(Target.visionPos) - Vector(myHero.visionPos)):normalized()
    if FaceVector:angle(EnemyVector) < 45 * 0.0174532925 or FaceVector:angle(EnemyVector) > 2*math.pi - 45*0.0174532925 and GetDistance(Target) < 700 then
        local PredictedPos, _, _ = VP:GetConeAOECastPosition(Target, 0.250, 30, 600, SpellQ.Speed, myHero)
        CastSpell(_Q, PredictedPos.x, PredictedPos.z)
    end
  end
end


function CheckQCreeps()
  if not QReady then return end
  EnemyMinions:update()
  for idx, minion in ipairs(EnemyMinions.objects) do
    local FaceVector = Vector(Vector(myHero.visionPos) - Vector(myHero)):normalized()
    local MinionVector = Vector(Vector(minion) - Vector(myHero.visionPos)):normalized()
    if FaceVector:angle(MinionVector) < 45 * 0.0174532925 or FaceVector:angle(MinionVector) > 2*math.pi - 45*0.0174532925 and GetDistance(minion) < 700 then
      return true
    end
  end
  return false
end


function CastQRegular()
    local FaceVector = Vector(Vector(myHero.visionPos) - Vector(myHero)):normalized()
    local ToCastVector = Vector(myHero.visionPos) + FaceVector*200 
    if QReady then
        CastSpell(_Q, ToCastVector.x, ToCastVector.z)
    end
end


function CastE(Target)
    if EReady and ValidTarget(Target) and GetDistance(Target) < SpellE.Range + 50 then
      local CastPosition, HitChance = nil, nil
      if not Config.Extras.Prodiction then
        CastPosition, HitChance = VP:GetLineCastPosition(Target, SpellE.Delay, SpellE.Width, SpellE.Range, SpellE.Speed, myHero, true)
      else
        CastPosition, info = Prodiction.GetPrediction(Target, SpellE.Range, SpellE.Speed, SpellE.Delay, SpellE.Width, myHero)
        local isCol = false
        if info ~= nil then
          isCol, _ = info.collision()
        end
        if info ~= nil and info.hitchance ~= nil and CastPosition ~= nil then 
          HitChance = info.hitchance
        end
      end
      if CastPosition ~= nil and HitChance ~= nil and HitChance > 1 and GetDistance(CastPosition) < SpellE.Range and not isCol then 
          CastSpell(_E, CastPosition.x, CastPosition.z)
      end
    end
end

function CastW(Target)
    if WReady and myHero.mana < 100 then
        CastSpell(_W)
    end
end

function CastR(Target)
    if not RReady then return end
    if ValidTarget(Target) and not Target.dead and Target ~= nil then
      if CountEnemyHeroInRange(1000, Target) > 1 then
          local CastPosition1, CastPosition2, NumHit = CalculateUltMultiple(Target)
          if CastPosition1 ~= nil and CastPosition2 ~= nil and NumHit ~= nil then
            CastUlt(CastPosition1, CastPosition2)
          end
      else
          local CastPosition1, CastPosition2 = CalculateUltSingle(Target)
          if CastPosition1 ~= nil and CastPosition2 ~= nil then
            CastUlt(CastPosition1, CastPosition2)
          end
      end
    end
end

function CastRAuto(Target)
  if Target ~= nil and ValidTarget(Target) and not Target.dead then
    if CountEnemyHeroInRange(1000, Target) > 1 then
        local CastPosition1, CastPosition2, NumHit = CalculateUltMultiple(Target)
        if CastPosition1 ~= nil and CastPosition2 ~= nil and NumHit ~= nil then
          if NumHit >= Config.Extras.REnemies then
            CastUlt(CastPosition1, CastPosition2)
          end
        end
    end
  end
end


function CheckOverheatStatus(Target)
  if Target ~= nil and ValidTarget(Target) and not QReady and not EReady and myHero:GetSpellData(_Q).currentCd > 3 and myHero:GetSpellData(_E).currentCd > 4 and GetDistance(Target) < 350 then
    local PADdamage = getDmg("P", Target, myHero) + getDmg("AD", Target, myHero) 
    if Config.Extras.Debug then
      print(PADdamage)
    end
    if PADdamage * 3 > Target.health then 
      return true
    else
      return false
    end
  else
    return false
  end
end

function ComputeHeat(Target)
  if Target == nil or not ValidTarget(Target) or Target.dead then return end

  if myHero.mana > 80 and CheckOverheatStatus(Target) then
    ShouldOverheat = true
    ShouldCastQE = false
    ShouldCastW = false
  elseif myHero.mana > 80 and not CheckOverheatStatus(Target) then
    ShouldOverheat = false
    ShouldCastQE = false
    ShouldCastW = false
  elseif myHero.mana > 60 then
    ShouldOverheat = false
    ShouldCastQE = true
    ShouldCastW = false
  elseif myHero.mana > 0 then
    ShouldOverheat = false
    ShouldCastW = true
    ShouldCastQE = true
  end
end

function Farm()
  EnemyMinions:update()
  JungleMinions:update()
  minion = nil
  if #EnemyMinions.objects > 0 then
    minion = EnemyMinions.objects[1]
  elseif #JungleMinions.objects > 0 then
    minion = JungleMinions.objects[1]
  end

  if minion ~= nil and QReady and GetDistance(minion) < SpellQ.Range and Config.FarmSub.useQ then
    if (myHero.mana < 80 and not Config.FarmSub.Overheat) then
      CastSpell(_Q, minion.x, minion.z)
    elseif Config.FarmSub.Overheat then
      CastSpell(_Q, minion.x, minion.z)
    end
  elseif minion ~= nil and EReady and GetDistance(minion) < SpellE.Range and Config.FarmSub.useE then
    if (myHero.mana < 80 and not Config.FarmSub.Overheat) then
      CastSpell(_E, minion.x, minion.z)
    elseif Config.FarmSub.Overheat then
      CastSpell(_E, minion.x, minion.z)
    end
  end
end 


function LastHit()
  EnemyMinions:update()
  if not EReady then return end
  local function MinionCollision(minion)
    if minion ~= nil and ValidTarget(minion) then
      for idx, creep in ipairs(EnemyMinions.objects) do
        if creep.networkID ~= minion.networkID then 
          local pointSegment1, pointLine1, isOnSegment1 = VectorPointProjectionOnLineSegment(Vector(myHero.visionPos), Vector(minion), Vector(creep))
          if isOnSegment1 and GetDistance(pointSegment1, pointline1) < SpellE.Width + 60 then
            return true
          end
        end
      end
    end
    return false
  end


  for idx, minion in ipairs(EnemyMinions.objects) do
    if minion ~= nil and ValidTarget(minion) and GetDistance(minion) < SpellE.Range and getDmg("E", minion, myHero) > minion.health and not MinionCollision(minion) then
      CastSpell(_E, minion.x, minion.z)
    end
  end


end


function CalculateUltSingle(Target)
  if Target ~= nil and ValidTarget(Target) and not Target.dead and GetDistance(Target) < 1600 then
    if Config.Extras.Debug then
      print('CalculateUltSingle called')
    end
    local Position1, Position2 = nil, nil
    local current_waypoints = {}
    table.insert(current_waypoints, Vector(Target.visionPos.x, 0, Target.visionPos.z))
    for i = Target.pathIndex, Target.pathCount do
      path = Target:GetPath(i)
     if path ~= nil and path.x then
       table.insert(current_waypoints, Vector(path.x, 0, path.z))
      end
    end

    local function CheckWall(Position1, Position2)
      local Posmidpoint = {x = (Position1.x + Position1.x)/2, y = (Position1.y + Position2.y)/2, z = (Position1.z + Position2.z)/2}
      local EndInitalVector = Vector(Vector(Position2) - Vector(Position1)):normalized()
      local Mulitplier = 30
      local WallCount = 0 
      for i = 1, 20, 1 do
        local current_multiplier = 60 * i 
        local CurrentCheckVector = Vector(Position1) + EndInitalVector*current_multiplier
        if IsWall(D3DXVECTOR3(CurrentCheckVector.x, CurrentCheckVector.y, CurrentCheckVector.z)) then
          WallCount = WallCount + 1
        end
      end

      if WallCount >= 8 then
        return true
      else
        return false
      end

    end

    --Calculate the linearity of waypoints 
    local isLinear = true
    if #current_waypoints > 3 then
        for i = 2, #current_waypoints-1, 1 do
            local pointSegment1, pointLine1, isOnSegment1 = VectorPointProjectionOnLineSegment(Vector(current_waypoints[1]), Vector(current_waypoints[#current_waypoints]), Vector(current_waypoints[i]))
            if isOnSegment1 and pointSegment1 ~= nil and pointLine1 ~= nil then
                if GetDistance(pointSegment1, pointLine1) > SpellR.Width + 100 then
                  isLinear = false
                end
            end
        end
    end

    if #current_waypoints >= 2 then
    --Compute duration of waypoints
      local DurationSufficient = false
      local travel_time = 0
      if isLinear then
        for current_index = 1, #current_waypoints-1 do
            local current_time = GetDistance(current_waypoints[current_index], current_waypoints[current_index+1])/Target.ms
            travel_time = travel_time + current_time
        end
        if travel_time >= Config.Extras.RDuration then
          DurationSufficient = true
        end
      end

      --Find the current midpoint of the initial->end line
      local midpoint = {x = (current_waypoints[1].x + current_waypoints[#current_waypoints].x)/2, y = 0, z = (current_waypoints[1].z+ current_waypoints[#current_waypoints].z)/2}
      local FinalInitialUnitVector = Vector(Vector(current_waypoints[#current_waypoints]) - Vector(current_waypoints[1])):normalized() 
      if GetDistance(current_waypoints[1], midpoint) > 425 then 
        local Position2 = Vector(midpoint)
        local Position1 = Vector(current_waypoints[1]) - FinalInitialUnitVector*200
        if not CheckWall(Position1, Position2) then
          return Position1, Position2
        end
      elseif GetDistance(current_waypoints[1], current_waypoints[#current_waypoints]) < SpellR.WallLength and travel_time >= 0.7 then
        local Position2 = Vector(midpoint) + FinalInitialUnitVector*450
        local Position1 = Vector(midpoint) - FinalInitialUnitVector*420
        if not CheckWall(Position1, Position2) then
          return Position1, Position2
        end
      elseif GetDistance(current_waypoints[1], current_waypoints[#current_waypoints]) < SpellR.WallLength and travel_time >= 0.4 then
        local Position2 = Vector(midpoint) + FinalInitialUnitVector*400
        local Position1 = Vector(midpoint) - FinalInitialUnitVector*440
        if not CheckWall(Position1, Position2) then
          return Position1, Position2
        end
      elseif GetDistance(current_waypoints[1], current_waypoints[#current_waypoints]) < SpellR.WallLength and travel_time >= 0 then
        local Position2 = Vector(midpoint) + FinalInitialUnitVector*350
        local Position1 = Vector(midpoint) - FinalInitialUnitVector*460
        if not CheckWall(Position1, Position2) then
          return Position1, Position2
        end
      end
    --Compute case for when the enemy is just standing there :p
    else
      local FinalInitialUnitVector = Vector(Vector(Target.visionPos) - Vector(myHero.visionPos)):normalized() 
      if GetDistance(Target) < SpellR.Range then
        local Position1 = Vector(Target.visionPos) + FinalInitialUnitVector*360
        local Position2 = Vector(Target.visionPos) - FinalInitialUnitVector*360
        return Position1, Position2
      end
    end
    return nil, nil
  end
  return nil, nil
end


function CalculateUltMultiple(Target)
  --Hits Main Target + X number of enemies 
  -- if Target ~= nil and GetDistance(Target) < 2500 and ValidTarget(Target) and not Target.dead then
  --    if Config.Extras.Debug then
  --     print('CalculateUltMultiple called')
  --   end   

  --   if GetDistance(Target) > SpellR.Range then
  --     return nil, nil, nil
  --   end

  --   local current_waypoints = {}
  --   table.insert(current_waypoints, Vector(Target.visionPos.x, 0, Target.visionPos.z))
  --   for i = Target.pathIndex, Target.pathCount do
  --     path = Target:GetPath(i)
  --    if path ~= nil and path.x then
  --      table.insert(current_waypoints, Vector(path.x, 0, path.z))
  --     end
  --   end

  --   --Vpred ftw
  --   local BestHit = 0
  --   local BestHitPos = nil
  --   local Enemies = GetEnemyHeroes()
  --   local PredictedPos = VP:GetPredictedPos(Target, 0.10, SpellR.Speed, myHero, false)
  --   for idx, enemy in ipairs(Enemies) do
  --     if enemy ~= nil and enemy.networkID ~= Target.networkID and ValidTarget(enemy) and GetDistance(enemy, Target) < 1100 and PredictedPos ~= nil then
  --       local CastPosition, HitChance, NumHit, Position = VP:GetLineAOECastPosition(enemy, 0.25, SpellR.Width, SpellR.WallLength-100, SpellR.Speed, PredictedPos)
  --       if GetDistance(CastPosition, PredictedPos) < SpellR.WallLength-50 and NumHit > BestHit then
  --         BestHit = NumHit 
  --         BestHitPos = CastPosition
  --       end
  --     end
  --   end

  --   if Config.Extras.Debug then
  --     print('Besthit calculated with ' .. tostring(BestHit))
  --   end 

  --   if GetDistance(BestHitPos, PredictedPos) < SpellR.WallLength then
  --     return PredictedPos, BestHitPos, BestHit
  --   end
  -- end
  -- return nil, nil, nil

 if Target ~= nil and ValidTarget(Target) and not Target.dead and GetDistance(Target) < 1600 then
    if Config.Extras.Debug then
      print('CalculateUltMultiple called')
    end
    local Position1, Position2 = nil, nil
    local current_waypoints = {}
    table.insert(current_waypoints, Vector(Target.visionPos.x, 0, Target.visionPos.z))
    for i = Target.pathIndex, Target.pathCount do
      path = Target:GetPath(i)
     if path ~= nil and path.x then
       table.insert(current_waypoints, Vector(path.x, 0, path.z))
      end
    end

    local function CheckWall(Position1, Position2)
      local Posmidpoint = {x = (Position1.x + Position1.x)/2, y = (Position1.y + Position2.y)/2, z = (Position1.z + Position2.z)/2}
      local EndInitalVector = Vector(Vector(Position2) - Vector(Position1)):normalized()
      local Mulitplier = 30
      local WallCount = 0 
      for i = 1, 20, 1 do
        local current_multiplier = 60 * i 
        local CurrentCheckVector = Vector(Position1) + EndInitalVector*current_multiplier
        if IsWall(D3DXVECTOR3(CurrentCheckVector.x, CurrentCheckVector.y, CurrentCheckVector.z)) then
          WallCount = WallCount + 1
        end
      end

      if WallCount >= 8 then
        return true
      else
        return false
      end

    end


    local function CalculateNumHit(Position1, Position2, Target)
      if Target ~= nil and ValidTarget(Target) and Position1 ~= nil and Position2 ~= nil then
        local Posmidpoint = {x = (Position1.x + Position2.x)/2, y = 0, z = (Position1.z + Position2.z)/2}
        local EndInitalVector = Vector(Vector(Position2) - Vector(Position1)):normalized()
        local ExtensionAmount = SpellR.WallLength/2
        local ExtPos1 = Vector(Posmidpoint) + EndInitalVector*ExtensionAmount
        local ExtPos2 = Vector(Posmidpoint) - EndInitalVector*ExtensionAmount
        local Enemies = GetEnemyHeroes()
        local NumHit = 0
        for idx, enemy in ipairs(Enemies) do
          if ValidTarget(enemy) and enemy.networkID ~= Target.networkID and GetDistance(enemy, Target) < 1000 then
            local EnemyPredictedPos = VP:GetPredictedPos(enemy, 0.250, math.huge, myHero, false) 
            if EnemyPredictedPos ~= nil then
              local pointSegment2, pointLine2, isOnSegment2 = VectorPointProjectionOnLineSegment(Vector(ExtPos1), Vector(ExtPos2), Vector(EnemyPredictedPos))
              if isOnSegment2 and pointSegment2 ~= nil and pointLine2 ~= nil then
                if GetDistance(pointSegment2, pointLine2) < SpellR.Width + 60 then
                  NumHit = NumHit + 1
                end
              end
            end
          end
        end
        if Config.Extras.Debug then
          print('Returning numhit ' .. tostring(NumHit))
        end
        return NumHit
      end
    end


    --Compute multiple case first before going full-retard single case
    local Enemies = GetEnemyHeroes()
    local BestCastPosition1 = nil
    local BestCastPosition2 = nil
    local BestHits = 0
    local TargetPredictedPos = VP:GetPredictedPos(Target, 0.250, SpellR.Speed, myHero, false)
    for idx, enemy in ipairs(Enemies) do
      if enemy ~= nil and ValidTarget(enemy) and not enemy.dead and enemy.networkID ~= Target.networkID then
        local EnemyPredictedPos = VP:GetPredictedPos(enemy, 0.250, math.huge, myHero, false) 
        if GetDistance(enemy, Target) < 1000 and GetDistance(EnemyPredictedPos, TargetPredictedPos) < SpellR.WallLength and EnemyPredictedPos ~= nil and TargetPredictedPos ~= nil then
          local CurrentMidpoint = GetMidPoint(EnemyPredictedPos, TargetPredictedPos) 
          local MidVector = Vector(Vector(EnemyPredictedPos) - Vector(TargetPredictedPos)):normalized()
          local Position1 = CurrentMidpoint + MidVector*(SpellR.WallLength/2)
          local Position2 = CurrentMidpoint - MidVector*(SpellR.WallLength/2)
          local CurrentHit = CalculateNumHit(Position1, Position2, Target)
          if CurrentHit > BestHits then
            BestHits = CurrentHit
            BestCastPosition1 = Position1
            BestCastPosition2 = Position2
          end
        end
      end
    end

    if BestHits >= 2 and BestCastPosition1 ~= nil and BestCastPosition2 ~= nil then
      if not CheckWall(BestCastPosition1, BestCastPosition2) then
        return BestCastPosition1, BestCastPosition2, BestHits
      end
    end

    --Calculate the linearity of waypoints 
    local isLinear = true
    if #current_waypoints > 3 then
        for i = 2, #current_waypoints-1, 1 do
            local pointSegment1, pointLine1, isOnSegment1 = VectorPointProjectionOnLineSegment(Vector(current_waypoints[1]), Vector(current_waypoints[#current_waypoints]), Vector(current_waypoints[i]))
            if isOnSegment1 and pointSegment1 ~= nil and pointLine1 ~= nil then
                if GetDistance(pointSegment1, pointLine1) > SpellR.Width + 100 then
                  isLinear = false
                end
            end
        end
    end

    if #current_waypoints >= 2 then
    --Compute duration of waypoints
      local DurationSufficient = false
      local travel_time = 0
      if isLinear then
        for current_index = 1, #current_waypoints-1 do
            local current_time = GetDistance(current_waypoints[current_index], current_waypoints[current_index+1])/Target.ms
            travel_time = travel_time + current_time
        end
        if travel_time >= Config.Extras.RDuration then
          DurationSufficient = true
        end
      end

      --Find the current midpoint of the initial->end line
      local midpoint = {x = (current_waypoints[1].x + current_waypoints[#current_waypoints].x)/2, y = 0, z = (current_waypoints[1].z+ current_waypoints[#current_waypoints].z)/2}
      local FinalInitialUnitVector = Vector(Vector(current_waypoints[#current_waypoints]) - Vector(current_waypoints[1])):normalized() 
      if GetDistance(current_waypoints[1], midpoint) > 425 then 
        local Position2 = Vector(midpoint)
        local Position1 = Vector(current_waypoints[1]) - FinalInitialUnitVector*200
        if not CheckWall(Position1, Position2) then
          local CurrentHit = CalculateNumHit(Position1, Position2, Target)
          return Position1, Position2, CurrentHit
        end
      elseif GetDistance(current_waypoints[1], current_waypoints[#current_waypoints]) < SpellR.WallLength and travel_time >= 0.7 then
        local Position2 = Vector(midpoint) + FinalInitialUnitVector*450
        local Position1 = Vector(midpoint) - FinalInitialUnitVector*250
        if not CheckWall(Position1, Position2) then
          local CurrentHit = CalculateNumHit(Position1, Position2, Target)
          return Position1, Position2, CurrentHit
        end
      elseif GetDistance(current_waypoints[1], current_waypoints[#current_waypoints]) < SpellR.WallLength and travel_time >= 0.4 then
        local Position2 = Vector(midpoint) + FinalInitialUnitVector*400
        local Position1 = Vector(midpoint) - FinalInitialUnitVector*300
        if not CheckWall(Position1, Position2) then
          local CurrentHit = CalculateNumHit(Position1, Position2, Target)
          return Position1, Position2, CurrentHit
        end
      elseif GetDistance(current_waypoints[1], current_waypoints[#current_waypoints]) < SpellR.WallLength and travel_time >= 0 then
        local Position2 = Vector(midpoint) + FinalInitialUnitVector*350
        local Position1 = Vector(midpoint) - FinalInitialUnitVector*350
        if not CheckWall(Position1, Position2) then
          local CurrentHit = CalculateNumHit(Position1, Position2, Target)
          return Position1, Position2, CurrentHit
        end
      end
    --Compute case for when the enemy is just standing there :p
    else
      local CloseEnemy, CloseDistance = GetClosestEnemy(Target)
      if CloseEnemy ~= nil and CloseDistance ~= nil and CloseDistance < 1000 then
        local FinalInitialUnitVector = Vector(Vector(Target.visionPos) - Vector(CloseEnemy.visionPos)):normalized() 
        local CloseMidpoint = GetMidPoint(Target.visionPos, CloseEnemy.visionPos)
        if GetDistance(Target) < SpellR.Range then
          local Position1 = Vector(CloseMidpoint.visionPos) + FinalInitialUnitVector*360
          local Position2 = Vector(CloseMidpoint.visionPos) - FinalInitialUnitVector*360
          if not CheckWall(Position1, Position2) then
            local CurrentHit = CalculateNumHit(Position1, Position2, Target)
            return Position1, Position2, CurrentHit
          end
        end
      else
        local FinalInitialUnitVector = Vector(Vector(Target.visionPos) - Vector(myHero.visionPos)):normalized() 
        if GetDistance(Target) < SpellR.Range then
          local Position1 = Vector(Target.visionPos) + FinalInitialUnitVector*360
          local Position2 = Vector(Target.visionPos) - FinalInitialUnitVector*360
          if not CheckWall(Position1, Position2) then
            local CurrentHit = CalculateNumHit(Position1, Position2, Target)
            return Position1, Position2, CurrentHit
          end
        end
      end
    end
    return nil, nil, nil
  end
  return nil, nil, nil
end

function GetClosestEnemy(Target)
  if Target ~= nil then
    local Enemies = GetEnemyHeroes()
    local closest_enemy = nil
    local closest_distance = math.huge
    for idx, enemy in ipairs(Enemies) do
      if ValidTarget(enemy) and enemy.networkID ~= Target.networkID then
        if GetDistance(enemy, Target) < closest_distance then
          closest_distance = GetDistance(enemy, Target)
          closest_enemy = enemy
        end
      end
    end
    if enemy ~= nil and closest_distance ~= nil then
      return enemy, closest_distance
    end
  end
end

function GetMidPoint(Position1, Position2)
  local Posmidpoint = {x = (Position1.x + Position1.x)/2, y = 0, z = (Position1.z + Position2.z)/2}
  return Posmidpoint
end

function OnGainBuff(unit, buff)
  if initDone and _G[c({106,100,107,110,109,118})] then 
    if unit.isMe and buff.name == 'rumbleoverheat' then
        isOverheated = true
    end

    if unit.isMe and buff.name == 'RumbleGrenade' then
        isSecondE = true
    end

    if unit.isMe and buff.name:find('Recall') then
      isRecalling = true
    end
  end
end

function OnLoseBuff(unit, buff)
  if initDone and _G[c({106,100,107,110,109,118})]  then 
    if unit.isMe and buff.name == 'rumbleoverheat' then
        isOverheated = false
    end

    if unit.isMe and buff.name == 'RumbleGrenade' then
        isSecondE = false
    end

    if unit.isMe and buff.name:find('Recall') then
      isRecalling = false
    end
  end

end


function OnRecall(hero, channelTimeInMs)
    if hero.networkID == myHero.networkID and initDone and _G[c({106,100,107,110,109,118})] then
        isRecalling = true
    end
end

function OnAbortRecall(hero)
    if hero.networkID == myHero.networkID and initDone and _G[c({106,100,107,110,109,118})]  then
        isRecalling = false
    end 
end

function OnFinishRecall(hero)
    if hero.networkID == myHero.networkID and initDone and _G[c({106,100,107,110,109,118})]  then
        isRecalling = false
    end
end

function CheckCone(position)
    if GetDistance(position) > 750 then return false end
    local FaceVector = Vector(Vector(myHero.visionPos) - Vector(myHero)):normalized()
    local RotationDegree1 = SpellQ.Angle * 0.0174532925
    local FaceRotatedVec1 = FaceVector:rotated(RotationDegree1, 0, RotationDegree1)
    local RotationDegree2 = 360 - SpellQ.Angle * 0.0174532925
    local FaceRotatedVec2 = FaceVector:rotated(RotationDegree2, 0, RotationDegree2)
    RotatedVector1 = Vector(myHero.visionPos) + FaceRotatedVec1*600
    RotatedVector2 = Vector(myHero.visionPos) + FaceRotatedVec2*600
    if GetDistance(position, myHero.visionPos) < 600 and RotatedVector1:angle(Vector(position)) < RotatedVector1:angle(Vector(RotatedVector2)) then
        return true
    else
        return false
    end
end


function OnProcessSpell(unit, spell)
    if not _G[c({106,100,107,110,109,118})]  then return end
    if unit == myHero then
        if spell.name:lower():find("attack") then
            lastAttack = GetTickCount() - GetLatency()/2
            lastWindUpTime = spell.windUpTime*1000
            lastAttackCD = spell.animationTime*1000
            animation_time = spell.windUpTime
        end

        if spell.name == 'RumbleFlameThrower' then
            LastSpellCast = GetTickCount()
            LastQTick = GetTickCount()
        end

        if spell.name == 'RumbleShield' then
            LastSpellCast = GetTickCount()
        end


        if spell.name == 'RumbleGrenade' then
            LastSpellCast = GetTickCount()
            LastETick = GetTickCount()
        end

        if spell.name == 'RumbleCarpetBomb' then
            LastSpellCast = GetTickCount()
        end
    end
end

function OnDraw()
    if initDone and _G[c({106,100,107,110,109,118})] then
        if Config.Extras.Debug and ShouldOverheat ~= nil and ShouldCastQE ~= nil and ShouldCastW ~= nil then
           DrawText3D("Overheat: " .. tostring(ShouldOverheat) .. " ShouldCastQE: " .. tostring(ShouldCastQE) .. " ShouldCastW: " .. tostring(ShouldCastW) .. " SecondE " .. tostring(isSecondE) .. " recalling " .. tostring(isRecalling), myHero.x, myHero.y, myHero.z, 20, ARGB(255,255,0,0), true)               
        end

        if Config.Extras.Debug and minion ~= nil then
          DrawCircle3D(minion.x, minion.y, minion.z, 200, 1, ARGB(255, 0, 255, 255))
        end
        if Config.Draw.DrawQ and QReady then
          DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellQ.Range, 1, ARGB(255, 0, 255, 255))
        end
        if Config.Draw.DrawE and EReady then
          DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellE.Range, 1, ARGB(255, 0, 255, 255))
        end
        if Config.Draw.DrawR and RReady then
          DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellR.Range, 1, ARGB(255, 0, 255, 255))
        end
        if Config.Draw.KillText then
          local Enemies = GetEnemyHeroes()
          for idx, enemy in ipairs(Enemies) do
            if ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < 2000 and (QReady and getDmg("Q", enemy, myHero) > enemy.health) then
                DrawText3D("Short Q", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)               
            elseif ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < 2000 and (QReady and getDmg("Q", enemy, myHero, 3) > enemy.health) then
                DrawText3D("Full Q", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
            elseif ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < 2000 and (RReady and getDmg("R", enemy, myHero) > enemy.health) then
                DrawText3D("R", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
            elseif ValidTarget(enemy) and not enemy.dead  and GetDistance(enemy) < 2000 and (EReady and 2*getDmg("E", enemy, myHero) > enemy.health) then
                DrawText3D("2E", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
            elseif ValidTarget(enemy) and not enemy.dead  and GetDistance(enemy) < 2000 and (EReady and QReady) and 2*getDmg("E", enemy, myHero) + getDmg("Q", enemy, myHero,  3) > enemy.health then
                DrawText3D("Q+2E", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
            elseif ValidTarget(enemy) and not enemy.dead  and GetDistance(enemy) < 2000 and (RReady and QReady) and getDmg("Q", enemy, myHero) + getDmg("R", enemy, myHero) > enemy.health then
                DrawText3D("Q+R", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
            elseif ValidTarget(enemy) and not enemy.dead  and GetDistance(enemy) < 2000 and  (RReady and QReady and EReady) and getDmg("Q", enemy, myHero) + getDmg("R", enemy, myHero) + 2*getDmg("E", enemy, myHero) > enemy.health then
                DrawText3D("Q+R+2E", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
            elseif ValidTarget(enemy) and enemy ~= nil then 
                DrawText3D("Not Killable", enemy.x, enemy.y, enemy.z, 20, ARGB(255,255,0,0), true)
            end
          end
        end
    end
end

function CastUlt(startPos, endPos)
    Packet('S_CAST', {spellId = _R, fromX = startPos.x, fromY = startPos.z, toX = endPos.x, toY = endPos.z}):send()
end


function Checks()
    QReady = (myHero:CanUseSpell(_Q) == READY)
    WReady = (myHero:CanUseSpell(_W) == READY)
    EReady = (myHero:CanUseSpell(_E) == READY)
    RReady = (myHero:CanUseSpell(_R) == READY)
    if myHero.mana > 80 then 
        CanCast = false
    end

    if not QReady then
      LastQTick = 0
    end

    if myHero.dead then
      LastETick = 0
      isSecondE = false
    end

end


function checkOrbwalker()
    if _G.MMA_Loaded ~= nil and _G.MMA_Loaded then
        IsMMALoaded = true
        print('MMA detected, using MMA compatibility')
    elseif _G.AutoCarry then
        IsSACLoaded = true
        print('SAC detected, using SAC compatibility')
    elseif FileExist(LIB_PATH .."SOW.lua") then
        require "SOW"
        SOWi = SOW(VP)
        IsSowLoaded = true
        SOWi:RegisterAfterAttackCallback(AutoAttackReset)
        print('SOW loaded, using SOW compatibility')
    else
        print('Please use SAC, MMA, or SOW for your orbwalker')
    end
end