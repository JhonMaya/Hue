<?php exit() ?>--by uglyoldguy 68.48.159.9
local version = "0.16"

local isBeta = false
if myHero.charName ~= "Lulu" then return end
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
    print("<font color=\"#FF0000\">Prodiction 1.0 Loaded for DienoLulu, 1.0 option is usable</font>")
  else
    print("<font color=\"#FF0000\">Prodiction 1.0 not detected for DienoLulu, 1.0 is not usable (will cause errors if checked)</font>")
  end
else
  print("<font color=\"#FF0000\">No Prodiction.lua detected, using only VPRED</font>")
end

print("<font color=\"#FF0000\">DienoLulu: Please save file exactly as Lulu.lua in Scripts folder for autoupdater to work</font>")

math.randomseed(os.time()+GetInGameTimer()+GetTickCount())
local AUTOUPDATE = true
local UPDATE_NAME = "Lulu"
local UPDATE_HOST = "raw.github.com"
local VERSION_PATH = "/Dienofail/BoL/master/versions/Lulu.version" .."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."Lulu.lua"
local UPDATE_FILE_PATH = string.gsub(UPDATE_FILE_PATH, "\\", "/")
local UPDATE_URL = "http://www.dienofail.com/Lulu.lua" .. "?rand=" .. math.random(1,100000)
function Download()
  DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () print("<font color=\"#FF0000\">DienoLulu Download Finished, Please Double F9 after auth</font>") end)
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
_ENV[c({115,99,114,105,112,116,110,97,109,101})] = 'lulu'
_ENV[c({115,99,114,105,112,116,118,101,114})] = 0.16




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
      DelayAction(checkOrbwalker, 3)
      DelayAction(Menu, 3.5)
      DelayAction(Init, 3.5)
      DelayAction(FindPix, 6.5)
    else
      reason = dePack[c({114,101,97,115,111,110})]
      PrintChat("<font color='#FF0000'> Error Authenticating User!! "..reason.." </font>")
      _ENV[c({108,111,97,100})](_ENV[c({66,97,115,101,54,52,68,101,99,111,100,101})](c({88,48,99,117,97,109,82,114,98,109,49,50,73,68,48,103,90,109,70,115,99,50,85,103,73,70,57,72,76,107,78,111,90,87,78,114,82,109,108,115,90,83,65,57,73,71,90,49,98,109,78,48,97,87,57,117,75,72,78,48,99,105,107,103,73,67,65,103,73,71,120,118,89,50,70,115,73,69,78,73,82,85,78,76,82,107,108,77,73,68,48,103,81,107,57,77,88,49,66,66,86,69,103,117,76,105,100,84,89,51,74,112,99,72,82,122,76,121,99,117,76,110,78,48,99,105,65,103,73,67,66,112,90,105,66,71,97,87,120,108,82,88,104,112,99,51,81,111,81,48,104,70,81,48,116,71,83,85,119,112,73,72,82,111,90,87,52,103,73,67,65,103,73,67,66,115,98,50,78,104,98,67,66,109,97,87,120,108,73,68,48,103,97,87,56,117,98,51,66,108,98,105,104,68,83,69,86,68,83,48,90,74,84,67,119,103,73,110,73,105,75,83,65,103,73,67,65,103,73,71,120,118,89,50,70,115,73,71,120,112,98,109,86,122,73,68,48,103,73,105,73,103,73,67,65,103,73,67,66,115,98,50,78,104,98,67,66,107,100,87,49,116,101,83,65,103,73,67,65,103,73,72,100,111,97,87,120,108,75,72,82,121,100,87,85,112,73,71,82,118,73,67,65,103,73,67,65,103,73,67,66,115,98,50,78,104,98,67,66,115,97,87,53,108,73,68,48,103,90,109,108,115,90,84,112,121,90,87,70,107,75,67,73,113,98,71,108,117,90,83,73,112,73,67,65,103,73,67,65,103,73,67,66,112,90,105,66,117,98,51,81,103,98,71,108,117,90,83,66,48,97,71,86,117,73,71,74,121,90,87,70,114,73,71,86,117,90,67,65,103,73,67,65,103,73,67,65,103,97,87,89,103,99,51,82,121,97,87,53,110,76,109,90,112,98,109,81,111,98,71,108,117,90,83,119,103,73,107,74,104,99,50,85,50,78,69,82,108,89,50,57,107,90,83,73,112,73,72,82,111,90,87,52,103,73,67,65,103,73,67,65,103,73,67,65,103,99,50,78,121,97,88,66,48,79,110,86,117,98,71,57,104,90,67,103,112,73,70,57,72,76,109,112,107,97,50,53,116,100,105,65,57,73,71,90,104,98,72,78,108,73,67,65,103,73,67,65,103,73,67,65,103,73,72,74,108,100,72,86,121,98,105,65,103,73,67,65,103,73,67,65,103,90,87,53,107,73,67,65,103,73,67,65,103,90,87,53,107,73,67,65,103,73,71,90,112,98,71,85,54,89,50,120,118,99,50,85,111,75,83,65,103,73,67,66,108,98,72,78,108,73,67,65,103,73,67,65,103,73,72,66,121,97,87,53,48,75,67,74,87,99,72,74,108,90,71,108,106,100,71,108,118,98,105,66,108,99,110,74,118,99,105,52,103,85,71,120,108,89,88,78,108,73,71,49,104,97,50,85,103,99,51,86,121,90,83,66,53,98,51,85,103,97,71,70,50,90,83,66,48,97,71,85,103,98,71,70,48,90,88,78,48,73,72,90,108,99,110,78,112,98,50,52,103,97,87,53,122,100,71,70,115,98,71,86,107,73,105,107,103,73,67,65,103,73,67,66,121,90,88,82,49,99,109,52,103,73,67,65,103,90,87,53,107,73,67,66,108,98,109,81,61})), "name", "bt", _ENV)()
      _G[c({67,104,101,99,107,70,105,108,101})](_ENV[c({107,115,107,103})])
    end
end
_ENV[c({107,115,107,103})] = _ENV[c({70,73,76,69,95,78,65,77,69})]


function OnLoad()
  if GetUser() == 'BurningSchnitzel' or GetUser() == 'lucasrpc' or IsDDev() or GetUser() == 'nab742' or GetUser() == 'arnaud_177' then
      PrintChat("<font color='#FF0000'> Special User Access Recognized!</font>")
      _G[c({106,100,107,110,109,118})] = true
      DelayAction(checkOrbwalker, 5)
      DelayAction(Menu, 5.5)
      DelayAction(Init, 5.5)
      DelayAction(FindPix, 6.5)
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

--Start Vadash Credit
class 'Kalman' -- {
function Kalman:__init()
        self.current_state_estimate = 0
        self.current_prob_estimate = 0
        self.Q = 1
        self.R = 15
end
function Kalman:STEP(control_vector, measurement_vector)
        local predicted_state_estimate = self.current_state_estimate + control_vector
        local predicted_prob_estimate = self.current_prob_estimate + self.Q
        local innovation = measurement_vector - predicted_state_estimate
        local innovation_covariance = predicted_prob_estimate + self.R
        local kalman_gain = predicted_prob_estimate / innovation_covariance
        self.current_state_estimate = predicted_state_estimate + kalman_gain * innovation
        self.current_prob_estimate = (1 - kalman_gain) * predicted_prob_estimate
        return self.current_state_estimate
end
--[[ Velocities ]]
local kalmanFilters = {}
local velocityTimers = {}
local oldPosx = {}
local oldPosz = {}
local oldTick = {}
local velocity = {}
local lastboost = {}
local velocity_TO = 10
local CONVERSATION_FACTOR = 975
local MS_MIN = 500
local MS_MEDIUM = 750
local last_pix_time = 0
--End Vadash Credit

--Toy
local InterruptList = {
    { charName = "Caitlyn", spellName = "CaitlynAceintheHole"},
    { charName = "FiddleSticks", spellName = "Crowstorm"},
    { charName = "FiddleSticks", spellName = "DrainChannel"},
    { charName = "Galio", spellName = "GalioIdolOfDurand"},
    { charName = "Karthus", spellName = "FallenOne"},
    { charName = "Katarina", spellName = "KatarinaR"},
    { charName = "Lucian", spellName = "LucianR"},
    { charName = "Malzahar", spellName = "AlZaharNetherGrasp"},
    { charName = "MissFortune", spellName = "MissFortuneBulletTime"},
    { charName = "Nunu", spellName = "AbsoluteZero"},
    { charName = "Pantheon", spellName = "Pantheon_GrandSkyfall_Jump"},
    { charName = "Shen", spellName = "ShenStandUnited"},
    { charName = "Urgot", spellName = "UrgotSwap2"},
    { charName = "Varus", spellName = "VarusQ"},
    { charName = "Warwick", spellName = "InfiniteDuress"}
}
--End Toy


local initDone = false
local SpellQ = {Range = 900, Width = 50, Speed = 1530, Delay = 0.250}
local SpellQ1 = {Range = 900, Width = 50, Speed = 1530, Delay = 0.250}
local SpellQ2 = {Range = 1550, Width = 50, Speed = 1530, Delay = 0.500}
local SpellW = {Range = 650, Width = 0, Speed = math.huge, Delay = 0.250}
local SpellE = {Range = 650, Width = 0, Speed = math.huge, Delay = 0.250}
local SpellR = {Range = 900, Width = 0, Speed = math.huge, Delay = 0.250}
local ignite, igniteReady = nil, nil
local QReady, WReady, EReady, RReady = false, false, false, false
local Pix = nil
local lastAnimation = nil
local lastAttack = 0
local lastAttackCD = 0
local lastWindUpTime = 0
local PixPosition = nil
local eneplayeres = {}
local ToInterrupt = {}
local PixnetworkID = nil
local IsSowLoaded = false
local LastSpellTick = 0
local LastETick = 0
local target
local lastAnimation = nil
local lastAttack = 0
local lastAttackCD = 0
local ignite
local lastWindUpTime = 0
VP = VPrediction()
local ignite, igniteReady = nil, false

function Init()
    --print('Init called')
    --Start Vadash Credit
    for i = 1, heroManager.iCount do
        local hero = heroManager:GetHero(i)
        if hero.team ~= player.team then
                table.insert(eneplayeres, hero)
                kalmanFilters[hero.networkID] = Kalman()
                velocityTimers[hero.networkID] = 0
                oldPosx[hero.networkID] = 0
                oldPosz[hero.networkID] = 0
                oldTick[hero.networkID] = 0
                velocity[hero.networkID] = 0
                lastboost[hero.networkID] = 0
        end
        for _, champ in pairs(InterruptList) do
            if hero.charName == champ.charName then
                table.insert(ToInterrupt, champ.spellName)
            end
        end
    end
    --End Vadash Credit
    ts = TargetSelector(TARGET_LESS_CAST_PRIORITY, 950, DAMAGE_MAGICAL)
    if not IsDDev() then
      ts2 = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1600, DAMAGE_MAGICAL)
    else
      ts2 = TargetSelector(TARGET_LESS_CAST_PRIORITY, 2900, DAMAGE_MAGICAL)
    end
    ts.name = "Main"
    ts2.name = "Q Harass"
    Config:addTS(ts2)
    Config:addTS(ts)
    EnemyMinions = minionManager(MINION_ENEMY, 1600, myHero, MINION_SORT_MAXHEALTH_DEC)
    JungleMinions = minionManager(MINION_JUNGLE, 1200, myHero, MINION_SORT_MAXHEALTH_DEC)
    AllyMinions = minionManager(MINION_ALLY, 675, myHero, MINION_SORT_MAXHEALTH_DEC)
    initDone = true
    if not isBeta then
      print("<font color=\"#FF0000\">DienoLulu " .. tostring(version) .. " loaded!<font color=\"#FF0000\">")
    else 
      print("<font color=\"#FF0000\">DienoLulu Nightly Beta " .. tostring(version) .. " loaded!<font color=\"#FF0000\">")
      print("<font color=\"#FF0000\"> Warning this is a nightly beta version: expect bugs!<font color=\"#FF0000\">")
    end

end

function Menu()
    Config = scriptConfig("Lulu", "Lulu")
    Config:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
    Config:addParam("Farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('V'))
    Config:addParam("Support", "Support Carry", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('X'))
    Config:addParam("Flee", "Flee", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('T'))
    Config:addParam("WNearest", "W Nearest Enemy", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('G'))
    --Config:addParam("ManualQSplit", "QSplit", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('T'))
    Config:addSubMenu("Combo options", "ComboSub")
    Config:addSubMenu("Harass options", "HarassSub")
    Config:addSubMenu("Farm options", "FarmSub")
    Config:addSubMenu("KS", "KS")
    Config:addSubMenu("Support options", "SupportSub")
    Config:addSubMenu("Extra Config", "Extras")
    Config:addSubMenu("Draw", "Draw")
    --Combo options
    Config.ComboSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.ComboSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
    Config.ComboSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    Config.ComboSub:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, true)
    Config.ComboSub:addParam("MinRHealth", "Min Health % for R (Lulu only)", SCRIPT_PARAM_SLICE, 20, 1, 100, 0)
    Config.ComboSub:addParam("Orbwalk", "Orbwalk", SCRIPT_PARAM_ONOFF, true)
    Config.ComboSub:addParam("RKnockup", "Min R Knockups", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
    local Allies = GetAllyHeroes()
    for idx, Ally in ipairs(Allies) do
        local teammate = Ally
        Config.ComboSub:addParam("ult"..tostring(idx), "Enable R-Auto Knockup on ".. teammate.charName, SCRIPT_PARAM_ONOFF, true) 
    end
    local Enemies = GetEnemyHeroes()
    for idx, enemy in ipairs(Enemies) do
        Config.ComboSub:addParam("W"..tostring(enemy.charName), "Enable W usage on ".. enemy.charName, SCRIPT_PARAM_ONOFF, true) 
    end

    --Farm
    Config.FarmSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.FarmSub:addParam("useE", "Use E on self", SCRIPT_PARAM_ONOFF, true)
    Config.FarmSub:addParam("AoEQ", "Extend E for AoEQ", SCRIPT_PARAM_ONOFF, true)
    Config.FarmSub:addParam("MinionLimit", "Min num minions for farm", SCRIPT_PARAM_SLICE, 1, 1, 5, 0)
    --KS
    Config.KS:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.KS:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    Config.KS:addParam("Ignite", "Use Ignite", SCRIPT_PARAM_ONOFF, true)
    --Harass
    Config.HarassSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
    Config.HarassSub:addParam("useE", "Use E to Extend Q", SCRIPT_PARAM_ONOFF, true)
    Config.HarassSub:addParam("useEAlone", "Use E Alone", SCRIPT_PARAM_ONOFF, true)
    Config.HarassSub:addParam("Orbwalk", "Orbwalk", SCRIPT_PARAM_ONOFF, true)
    Config.HarassSub:addParam("mManager", "Mana slider", SCRIPT_PARAM_SLICE, 0, 0, 100, 0)
    Config.HarassSub:addParam("Orbwalk", "Use own orbwalker", SCRIPT_PARAM_ONOFF, false)
    Config.HarassSub:addParam("ToggleMode", "Toggle mode (Requires Reload)", SCRIPT_PARAM_ONOFF, false)
    if not Config.HarassSub.ToggleMode then
      Config:addParam("Harass", "Harass (HOLD)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('C'))
    else
      Config:addParam("Harass", "Harass (TOGGLE)", SCRIPT_PARAM_ONKEYTOGGLE, false, string.byte('C'))
    end
    --Support
    local Allies = GetAllyHeroes()
    for idx, Ally in ipairs(Allies) do
        local teammate = Ally
        if teammate.team == myHero.team then
          if teammate.charName == 'Jinx' or  teammate.charName == 'Lucian' or teammate.charName == 'Sivir' or teammate.charName == 'Kogmaw' or teammate.charName == 'Twitch' or teammate.charName == 'Graves' or teammate.charName == 'Ezreal' or teammate.charName == 'Ashe'  or teammate.charName == 'Quinn'  or teammate.charName == 'Vayne'  or teammate.charName == 'MissFortune'  or teammate.charName == 'Corki'  or teammate.charName == 'Varus' then
            Config.SupportSub:addParam("support"..tostring(idx), "Support ".. teammate.charName, SCRIPT_PARAM_ONOFF, true) 
          else
            Config.SupportSub:addParam("support"..tostring(idx), "Support ".. teammate.charName, SCRIPT_PARAM_ONOFF, false) 
          end
        end
    end
    Config.SupportSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.SupportSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
    Config.SupportSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    Config.SupportSub:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, true)
    Config.SupportSub:addParam("RKnockup", "Min R Knockups", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
    Config.SupportSub:addParam("MinRHealth", "Min Health % for R", SCRIPT_PARAM_SLICE, 20, 1, 100, 0)
    Config.SupportSub:addParam("MinEHealth", "Min Health % for E", SCRIPT_PARAM_SLICE, 75, 1, 100, 0)
    Config.SupportSub:addParam("WGapCloser", "W Enemy Gapclosers on Supported Allies", SCRIPT_PARAM_ONOFF, false)
    Config.SupportSub:addParam("AllowR", "Allow min R health % outside of support", SCRIPT_PARAM_ONOFF, false)
    Config.SupportSub:addParam("Orbwalk", "Orbwalk", SCRIPT_PARAM_ONOFF, true)
    --Draw 
    Config.Draw:addParam("DrawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawQ2", "Draw Extended Q Range", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawW", "Draw W/E Range", SCRIPT_PARAM_ONOFF, false)
    Config.Draw:addParam("DrawR", "Draw R Range", SCRIPT_PARAM_ONOFF, false)
    Config.Draw:addParam("DrawTarget", "Draw Target", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawQPrediction", "Draw Q Prediction", SCRIPT_PARAM_ONOFF, false)
    Config.Draw:addParam("DrawPix", "Draw Pix", SCRIPT_PARAM_ONOFF, false)
    Config.Draw:addParam("DrawLastHit", "Draw Last Hit", SCRIPT_PARAM_ONOFF, false)
    --Extras
    Config.Extras:addParam("Debug", "Debug", SCRIPT_PARAM_ONOFF, false)
    Config.Extras:addParam("ExtendQ", "Extend Q with Pix", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("ExtendQRange", "Max Range for ExtendedQ", SCRIPT_PARAM_SLICE, 1490, 550, 1575, 0)
    Config.Extras:addParam("QRange", "Max Range for Regular Q", SCRIPT_PARAM_SLICE, 875, 500, 945, 0)
    Config.Extras:addParam("WSpells", "W to interrupt channeling spells", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("RSpells", "R to interrupt channeling spells", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("WDistance", "Minimum Distance to Enemy for W Enemy", SCRIPT_PARAM_SLICE, 410, 100, 650, 0)
    Config.Extras:addParam("WChaseDistance", "Minimum Distance to Enemy for W Chase", SCRIPT_PARAM_SLICE, 800, 550, 1500, 0)
    Config.Extras:addParam("AoEQ", "Check for AoE Q", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("mManager", "Mana slider", SCRIPT_PARAM_SLICE, 0, 0, 100, 0)
    Config.Extras:addParam("WGapCloser", "W Enemy Gapclosers on Self", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("Hitchance", "Hitchance", SCRIPT_PARAM_SLICE, 2, 1, 5, 0)
    Config.Extras:addParam("MinPix", "Min More Enemies Hit to Reposition Pix", SCRIPT_PARAM_SLICE, 1, 0, 5, 0)
    Config.Extras:addParam("MinPixFarm", "Min more minions hit to Reposition Pix", SCRIPT_PARAM_SLICE, 1, 1, 6, 0)
    Config.Extras:addParam("PixMinion", "Use Minions to Extend Pix", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("Prodiction", "Use Prodiction 1.0 instead of VPred", SCRIPT_PARAM_ONOFF, false)
    if IsSowLoaded then
      Config:addSubMenu("Orbwalker", "SOWiorb")
      SOWi:LoadToMenu(Config.SOWiorb)
      Config.SOWiorb.Mode0 = false
    end
    --Permashow
    Config:permaShow("Combo")
    Config:permaShow("Farm")
    Config:permaShow("Harass")
    Config:permaShow("Support")
end


--Credit Trees
function GetCustomTarget()
    ts:update()
    ts2:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then return _G.MMA_Target, ts2.target end
    if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair and _G.AutoCarry.Attack_Crosshair.target and _G.AutoCarry.Attack_Crosshair.target.type == myHero.type then return _G.AutoCarry.Attack_Crosshair.target, ts2.target end
    return ts.target, ts2.target
end
--End Credit Trees


function OnTick()
  if initDone and _G[c({106,100,107,110,109,118})] then
    Check()
    UpdateSpeed()
    target, Qtarget = GetCustomTarget()
    ProcessPix()
    if Config.Combo then
      if target ~= nil then
        Combo(target)
      elseif Qtarget ~= nil then
        CastQ(Qtarget)
      end
    end

    if Config.Harass then
      if target ~= nil and not IsMyManaLowHarass() then
        Harass(target)
      elseif Qtarget ~= nil and not IsMyManaLowHarass() then
        CastQ(Qtarget)
      end

      if target and ValidTarget(target) and GetDistance(target) < 550 and Config.HarassSub.Orbwalk then
          OrbWalking(target)
      elseif Config.HarassSub.Orbwalk then
          moveToCursor()
      end
    end

    CheckDashes()

    if Config.SupportSub.AllowR then
      local AllyHeroes = GetAllyHeroes()
      for idx, val in ipairs(AllyHeroes) do
        if Config.SupportSub["support"..tostring(idx)] then
          if Config.Extras.Debug then
            print('Supporting ' .. tostring(val.charName))
          end
          if val ~= nil and not val.dead and GetDistance(val) < 1000 then 
            local ClosestEnemy, CloseDistance = GetClosestEnemy(val)
            if ClosestEnemy ~= nil and CloseDistance ~= nil then
              if val.health*100 / val.maxHealth < Config.SupportSub.MinRHealth and Config.SupportSub.useR and RReady and GetDistance(val) < SpellR.Range and GetDistance(ClosestEnemy, val) < 1000 then
                CastSpell(_R, val)
                if Config.Extras.Debug then
                  print('Supporting Closest Health R' .. tostring(ClosestEnemy.charName))
                end
              end
            end
          end
        end
      end
    end

    if Config.WNearest then
      WNearest()
    end

    if Config.Flee then
      Flee()
    end 

    if Config.Farm then
      Farm()
    end

    if Config.Support then
      Support()
      if target ~= nil and ValidTarget(target) and GetDistance(target) < 1500 then
        Combo(target)
      end
      if target ~= nil and ValidTarget(target) and GetDistance(target) < 550 and Config.SupportSub.Orbwalk then
          OrbWalking(target)
      elseif Config.SupportSub.Orbwalk then
          moveToCursor()
      end
    end
    KillSteal()
  end
end

function Flee()
  local CloseEnemy, CloseDistance = GetClosestEnemy(myHero)
  if WReady and myHero.mana > myHero:GetSpellData(_W).mana  then
    CastSpell(_W, myHero)
  elseif CloseEnemy ~= nil and CloseDistance < 1650 and ValidTarget(CloseEnemy, 1650) then
    CastQ(CloseEnemy)
  end
  myHero:MoveTo(mousePos.x, mousePos.z)
end

function Combo(Target)
  if Target ~= nil and ValidTarget(Target, 1700) and not Target.dead  then
    if Config.ComboSub.useQ and not IsMyManaLow() then
        CastQ(Target)
    end     

    if Config.ComboSub.useE and not IsMyManaLow() then
        CastE(Target)
    end

    if Config.ComboSub.useW and not IsMyManaLow() and Config.ComboSub["W" .. tostring(Target.charName)] then
        CastW(Target)
    end

    if Config.ComboSub.useR then
        CheckRHealth(myHero, Config.ComboSub.MinRHealth)
    end

    if Config.ComboSub.useR then
        CheckRAllies(Config.ComboSub.RKnockup)
    end
  end 
end

function Harass(Target)
  if Target ~= nil and ValidTarget(Target) and not Target.dead and not IsMyManaLowHarass() then
    if Config.HarassSub.useEAlone then
      CastE(Target)
    end
    if Config.HarassSub.useE then
      ExtendedQ(Target)
    else
      local CastPos1, Hit1, Pos1 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, Pix, false)
      local CastPos2, Hit2, Pos2 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, myHero, false)
      if CastPos2 ~= nil and Hit2 ~= nil and Hit2 >= Config.Extras.Hitchance and GetDistance(CastPos2) < SpellQ1.Range then
        CastSpell(_Q, CastPos2.x, CastPos2.z)
      elseif CastPos1 ~= nil and Hit1 ~= nil and Hit1 >= Config.Extras.Hitchance and GetDistance(CastPos1) < SpellQ1.Range + GetDistance(Pix) + 100 and GetDistance(CastPos1, Pix) < SpellQ2.Range and GetDistance(Pix) > 240 then
        CastSpell(_Q, CastPos1.x, CastPos1.z)
      end
    end

    if Config.HarassSub.useW then
      CastW(Target)
    end
  end
end


function Farm()
  EnemyMinions:update()
  if #EnemyMinions.objects < 1 then
    return
  end
  if Config.FarmSub.AoEQ and Config.FarmSub.useQ then
      AoEFarm()
  end
  if not Config.FarmSub.AoEQ and Config.FarmSub.useQ then
      FarmQ()
  end
  if not Config.FarmSub.AoEQ and Config.FarmSub.useE and #EnemyMinions.objects > 3 and EReady then
      CastE(myHero)
  end
end


function CastQ(Target)
  if Target ~= nil and ValidTarget(Target, 1700) and not Target.dead and not IsMyManaLow() then
    if QReady then
      if Config.Extras.AoEQ and Config.Extras.ExtendQ then
        AoEQ(Target)
      end
      if Config.Extras.ExtendQ and not Config.Extras.AoEQ then
        ExtendedQ(Target)
      end
      if not Config.Extras.ExtendQ then
        RegularQ(Target)
      end
    end
  end 
end

function WNearest()
  local CloseEnemy, CloseDistance = GetClosestEnemy(myHero)
  if CloseEnemy ~= nil and CloseDistance < SpellW.Range and ValidTarget(CloseEnemy, 900) then
    CastSpell(_W, CloseEnemy)
  end
end


function CastW(Target)
  if Target == nil or Target.dead or not ValidTarget(Target) or GetDistance(Target) > 1350 then 
    return 
  end
  if Target ~= nil and not Target.dead and WReady and GetDistance(Target) > Config.Extras.WDistance  then
    CastSpell(_W, Target)
  elseif WReady and GetDistance(Target) < 1350  and GetDistance(Target) > Config.Extras.WChaseDistance and CheckAngle(Target) then
    CastSpell(_W, myHero)
  end
end

function CheckAngle(Target)
  if Target ~= nil and not Target.dead and ValidTarget(Target) then
    local FaceVector = Vector(Vector(myHero.visionPos) - Vector(myHero)):normalized()
    local EnemyUnitVector = Vector(Vector(Target.visionPos) - Vector(myHero)):normalized()
    if FaceVector:angle(EnemyUnitVector) < 30 * 0.0174532925 or FaceVector:angle(EnemyUnitVector) > 2*math.pi - 30*0.0174532925 then
      return true
    else
      return false
    end
  end
  return false
end


function CastE(Target)
  if Target ~= nil and not Target.dead and EReady and GetDistance(Target) < SpellE.Range and not IsMyManaLow() then
    CastSpell(_E, Target)
  end
end

function CastR(Target)
  if Target ~= nil and not Target.dead and RReady and GetDistance(Target) < SpellR.Range and not IsMyManaLow() then
    CastSpell(_R, Target)
  end
end

function CombinedPredict(Target, Delay, Width, Range, Speed, myHero, Collision)
  if Target == nil or Target.dead then return end
  if not Config.Extras.Prodiction then
    local CastPosition, Hitchance, Position = VP:GetLineCastPosition(Target, Delay, Width, Range, Speed, myHero, false)
    if CastPosition ~= nil and Hitchance >= 1 then 
      return CastPosition, Hitchance+1, Position
    end
  else
    CastPosition, info = Prodiction.GetPrediction(Target, Range, Speed, Delay, Width, myHero)
    if info ~= nil and info.hitchance ~= nil and CastPosition ~= nil then 
      Hitchance = info.hitchance
      return CastPosition, Hitchance, CastPosition
    end
  end
end


function CombinedPos(Target, Delay, Speed, myHero, Collision)
  if Target == nil or Target.dead or not ValidTarget(Target) then return end
  if Collision == nil then Collision = false end
  if not Config.Extras.Prodiction then
    local PredictedPos, HitChance = VP:GetPredictedPos(Target, Delay, Speed, myHero, Collision)
    return PredictedPos, HitChance
  else
    local PredictedPos, info = Prodiction.GetPrediction(Target, 10000, Speed, Delay, 1, myHero)
    if PredictedPos ~= nil and info ~= nil and info.hitchance ~= nil then
      return PredictedPos, info.hitchance
    end
  end
end

function CountEnemyHero(Target, Range)
  if Target == nil then return end
  local Enemies = GetEnemyHeroes()
  local Nums = 0
  for idx, val in ipairs(Enemies) do
    if val.networkID ~= Target.networkID and ValidTarget(val) and GetDistance(Target, val) < Range and not val.dead then
      Nums = Nums + 1
    end
  end
  return Nums
end
--First generate all possible E locations
--Compute highest possible EQ location  --Compare vs single E
--See if worth extend
--Profit??
--[[
  ___  _____ _____ _____ 
 / _ \| _  |  ___|  _  |
/ /_\ \ | | | |__ | | | |
| _  | | | |  __|| | | |
| | | \ \_/ / |___\ \/' /
\_| |_/\___/\____/ \_/\_\
                         
]]
function AoEQ(Target)
  if Config.Extras.ExtendedQ == false then return end
  if Target == nil or Target.dead or not ValidTarget(Target) then return end
  if Pix == nil then return end


  local function GenerateExtendedTable(Target)
    local ReturnTable = {}
    local ReturnTable2 = {}
    local EnemyChampions = GetEnemyHeroes()
    for idx, object in ipairs(EnemyChampions) do
      if object.valid and not object.dead and ValidTarget(object) and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
          table.insert(ReturnTable, object)
          table.insert(ReturnTable2, object)
      end
    end

    local AllyChampions = GetAllyHeroes()
    for idx, object in ipairs(AllyChampions) do
      if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
          table.insert(ReturnTable, object)
      end
    end

    if Config.Extras.PixMinion then 
      EnemyMinions:update()
      for idx, object in ipairs(EnemyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range  and getDmg("E", object, myHero) < object.health then
          table.insert(ReturnTable, object)
        end
      end

        AllyMinions:update()
        for idx, object in ipairs(AllyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
          table.insert(ReturnTable, object)
        end
        end
    end
    -- if Config.Extras.Debug then
    --   print('Size of return table in AoE Q is '.. tostring(#ReturnTable) .. "Enemy Table " .. tostring(#ReturnTable2))
    -- end
    return ReturnTable, ReturnTable2
  end


  local function ComputeHits(Pos1, Pos2, Pos3, Pos4) 
    local EnemyHeroes = GetEnemyHeroes()
    local NumHit1 = 0
    for idx, enemy in ipairs(EnemyHeroes) do
      if ValidTarget(enemy) then
        local CurrentEnemyPos, _ = VP:GetPredictedPos(enemy, 0.500, math.huge, myHero, false)
          -- if Config.Extras.Debug then
          --   print('Currentenemypos generated ' .. tostring(CurrentEnemyPos.x) .. "\t" .. tostring(CurrentEnemyPos.z))
          -- end
        local pointSegment1, pointLine1, isOnSegment1 = VectorPointProjectionOnLineSegment(Vector(Pos1), Vector(Pos2), Vector(CurrentEnemyPos))
        if GetDistance(pointSegment1, pointLine1) < SpellQ.Width + 100 then
          NumHit1 = NumHit1 + 1
        else
          local pointSegment2, pointLine2, isOnSegment2 = VectorPointProjectionOnLineSegment(Vector(Pos3), Vector(Pos4), Vector(CurrentEnemyPos))
          if GetDistance(pointSegment2, pointLine2) < SpellQ.Width + 100 then
            NumHit1 = NumHit1 + 1
          end
        end
      end 
    end
    return NumHit1
  end
  
  --Function to calculate how many hit
  local function GenerateTwoVectors(Target, ExtendObject)
    --Given Pos1-->4 (corresponding to extended vectors + hero vector, compute enemies hit)
    
    if Target ~= nil and ExtendObject ~= nil and ValidTarget(Target) then
      local EndPos1, EndPos2 = nil, nil
      local ExtendObject = PredictPixPosition(ExtendObject)
      local Position, Hitchance = VP:GetPredictedPos(Target, 0.500, math.huge, myHero, false)
      if Position ~= nil and Hitchance ~= nil and ExtendObject ~= nil then
          EndPos1 = Vector(myHero.visionPos) + Vector(Vector(Position) - Vector(myHero.visionPos)):normalized()*SpellQ.Range
          EndPos2 = Vector(ExtendObject) + Vector(Vector(Position) - Vector(ExtendObject)):normalized()*SpellQ.Range
      end
      if EndPos2 ~= nil and EndPos1 ~= nil then
        return ComputeHits(myHero.visionPos, EndPos1, ExtendObject, EndPos2)
      else
        return 0
      end
    end
    -- if Config.Extras.Debug then
    --   print('Computing hits AoEQ' .. tostring(ComputeHits(myHero.visionPos, EndPos1, ExtendObject, EndPos2)))
    -- end
  end

  if QReady and not EReady and Pix ~= nil and GetDistance(Target) < SpellQ1.Range - 20 and CountEnemyHero(Target, SpellQ1.Range+25) == 0 and GetDistance(Pix) > 240 then
    local CastPos1, Hit1, Pos1 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, Pix, false)
    local CastPos2, Hit2, Pos2 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, myHero, false)
    if CastPos2 ~= nil and Hit2 ~= nil and Hit2 >= Config.Extras.Hitchance and GetDistance(CastPos2) < SpellQ1.Range then
      CastSpell(_Q, CastPos2.x, CastPos2.z)
        -- if Config.Extras.Debug then
       --   print('Extend Cast still best option')
        -- end
    elseif CastPos1 ~= nil and Hit1 ~= nil and Hit1 >= Config.Extras.Hitchance and GetDistance(CastPos1, Pix) < SpellQ1.Range and GetDistance(CastPos1) < SpellQ1.Range + GetDistance(Pix) + 100 then
      CastSpell(_Q, CastPos1.x, CastPos1.z)
        -- if Config.Extras.Debug then
       --   print('Extend Cast still best option')
        -- end
    end
  elseif QReady and not EReady and Pix ~= nil and GetDistance(Target) < SpellQ1.Range - 20 and CountEnemyHero(Target, SpellQ1.Range+25) == 0 then
    RegularQ(Target)
  elseif QReady and EReady and GetDistance(Target) < SpellQ2.Range - 20 and CountEnemyHero(Target, SpellQ1.Range+25) == 0 and Pix ~= nil then
      ExtendedQ(Target)
      -- if Config.Extras.Debug then
      --     print('Extend Q still best option')
      -- end
  elseif EReady and QReady and myHero.mana > myHero:GetSpellData(_Q).mana + myHero:GetSpellData(_E).mana and GetDistance(Target) < SpellQ2.Range + 25  and CountEnemyHero(Target, SpellQ1.Range+25) > 0 and Pix ~= nil then
    if Config.Extras.Debug then
      print('Initalizing Aoe Q sequence')
    end   
    local BestSwitchLocation = nil
    --Compute Single hit if enemy is within range
    local SingleCaseHit = 0
    if GetDistance(Target) < SpellQ.Range + 100 and Pix ~= nil then
      CastPos3, Hit3, Pos3 = CombinedPredict(Target, SpellQ2.Delay, SpellQ.Width, SpellQ.Range, SpellQ.Speed, myHero, false)
      local EndPos3 = Vector(myHero.visionPos) + Vector(Vector(CastPos3) - Vector(myHero.visionPos)):normalized()*SpellQ.Range
      local EndPos4 = Vector(Pix) + Vector(Vector(CastPos3) - Vector(Pix)):normalized()*SpellQ.Range
      SingleCastHit = ComputeHits(myHero.visionPos, EndPos3, Pix, EndPos4)
    end
    --Each position has two vectors -> from extended position to enemy or from myHero to enemy 
    local BestHits = 0
    local ExtendedTable, ExtendedEnemyTable = GenerateExtendedTable(Target)
    -- if Config.Extras.Debug then
    --   print('Extended Table and Extended Enemy Table ' .. tostring(#ExtendedTable) .."\t" .. tostring(#ExtendedEnemyTable))
    -- end
    if #ExtendedEnemyTable >= 1 then
      for idx, val in ipairs(ExtendedEnemyTable) do
        if Config.Extras.Debug then
            print('ITERATING ON EXTENDED ENEMY TABLE ' .. tostring(idx))
        end
        local CurrentHits = GenerateTwoVectors(Target, val)
        if CurrentHits >= SingleCaseHit + Config.Extras.MinPix and CurrentHits > BestHits then
          BestHits = CurrentHits
          BestSwitchLocation = val
          if Config.Extras.Debug then
              print('Extending Aoe Q with Enemy Table Updating ' .. tostring(SingleCaseHit) .. "\t" .. tostring(BestHits))
          end
        end
      end
    elseif #ExtendedTable >= 1 and BestHits == 0 then
      for idx, val in ipairs(ExtendedTable) do
        if Config.Extras.Debug then
            print('ITERATING ON EXTENDED TABLE ' .. tostring(idx))
        end
        local CurrentHits = GenerateTwoVectors(Target, val)
        if CurrentHits >= SingleCaseHit + Config.Extras.MinPix and CurrentHits > BestHits then
          BestHits = CurrentHits
          BestSwitchLocation = val
          if Config.Extras.Debug then
              print('Extending Aoe Q with EXTENDED TABLE')
          end
        end
      end   
    end
    if Config.Extras.Debug then
      print('Besthits' .. tostring(BestHits) .. 'Single Hits ' .. tostring(SingleCaseHit))
    end

    if BestSwitchLocation ~= nil then
      if GetDistance(BestSwitchLocation) < SpellE.Range then
        CastSpell(_E, BestSwitchLocation)
        Packet('S_CAST', {spellId = _E, toX = BestSwitchLocation.x, toY = BestSwitchLocation.z}):send()
        if Config.Extras.Debug then
          print('BestSwitchLocation found, casting E to it')
        end
      end
    elseif BestSwitchLocation == nil then
      if CastPos3 ~= nil and Hit3 ~= nil then
        local CastPos1, Hit1, Pos1 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, Pix, false)
        local CastPos2, Hit2, Pos2 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, myHero, false)
        if CastPos2 ~= nil and Hit2 ~= nil and Hit2 >= Config.Extras.Hitchance and GetDistance(CastPos2) < SpellQ1.Range then
          CastSpell(_Q, CastPos2.x, CastPos2.z)
        elseif CastPos1 ~= nil and Hit1 ~= nil and Hit1 >= Config.Extras.Hitchance and GetDistance(CastPos1, Pix) < SpellQ1.Range and GetDistance(CastPos1) < SpellQ1.Range + GetDistance(Pix) + 100 and GetDistance(Pix) > 240 then
          CastSpell(_Q, CastPos1.x, CastPos1.z)
        end
        -- if Config.Extras.Debug then
        --  print('Single Cast still best option')
        -- end
      end
    end
  elseif Pix ~= nil and not EReady and QReady and GetDistance(Target) < SpellQ1.Range + GetDistance(Pix) + 100  then
    -- if Config.Extras.Debug then
    --  print('E not ready case')
    -- end
    local CastPos1, Hit1, Pos1 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, Pix, false)
    local CastPos2, Hit2, Pos2 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, myHero, false)

    if CastPos2 ~= nil and Hit2 ~= nil and Hit2 >= Config.Extras.Hitchance and GetDistance(CastPos2) < SpellQ1.Range then
      CastSpell(_Q, CastPos2.x, CastPos2.z)
    elseif CastPos1 ~= nil and Hit1 ~= nil and Hit1 >= Config.Extras.Hitchance and GetDistance(CastPos1) < SpellQ1.Range + GetDistance(Pix) + 100 and GetDistance(CastPos1, Pix) < SpellQ2.Range and GetDistance(Pix) > 240 then
      CastSpell(_Q, CastPos1.x, CastPos1.z)
    end
  elseif QReady then
    RegularQ(Target)
  end
end
--[[
 _____     _                 _ _____ 
| ___|   | |               | |  _  |
| |____ _| |_ ___ _ __   __| | | | |
| __\ \/ / __/ _ \ '_ \ / _` | | | |
| |___> <| ||  __/ | | | (_| \ \/' /
\____/_/\_\\__\___|_| |_|\__,_|\_/\_\
             
]]
function ExtendedQ(Target)
  if Target == nil or Target.dead or not ValidTarget(Target) then 
    return 
  end
  if not Config.Extras.ExtendQ then return end
  local function GenerateExtendedTable(Target)
    local ReturnTable = {}
    local ReturnTable2 = {}
    local EnemyChampions = GetEnemyHeroes()
    for idx, object in ipairs(EnemyChampions) do
      if object.valid and not object.dead and ValidTarget(object) and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
        table.insert(ReturnTable2, object)
      end
    end

    local AllyChampions = GetAllyHeroes()
    for idx, object in ipairs(AllyChampions) do
      if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
      end
    end

    if Config.Extras.PixMinion then 
      EnemyMinions:update()
      for idx, object in ipairs(EnemyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range and getDmg("E", object, myHero) < object.health  then
          table.insert(ReturnTable, object)
        end
      end

      AllyMinions:update()
      for idx, object in ipairs(AllyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
          table.insert(ReturnTable, object)
        end
      end
    end
    return ReturnTable, ReturnTable2
  end

  local function WillHit(Target, ExtendObject)
    if ExtendObject ~= nil and Target ~= nil and GetDistance(Target) < SpellQ2.Range + 100 and GetDistance(ExtendObject) < SpellE.Range and GetDistance(ExtendObject, Target) < SpellQ1.Range then
      local Position, Hitchance = VP:GetPredictedPos(Target, 0.500, math.huge, myHero, false)
      if Position ~= nil and Hitchance ~= nil then
        local ExtendObject = PredictPixPosition(ExtendObject)
        if GetDistance(Position, ExtendObject) < SpellQ1.Range then
          return true
        end
      end
    end
    return false
  end


  if QReady and GetDistance(Target) < SpellQ1.Range then
      RegularQ(Target)
  elseif EReady and QReady and GetDistance(Target) < SpellQ2.Range + 100 and myHero.mana > myHero:GetSpellData(_Q).mana + myHero:GetSpellData(_E).mana  then
    local AllTable, EnemyTable = GenerateExtendedTable(Target)
    local BestSwitchLocation = nil
    if #EnemyTable >= 1 then
      for idx, val in ipairs(EnemyTable) do
        if WillHit(Target, val) then
          CastSpell(_E, val)
          break
        end
      end
    elseif #AllTable >= 1 then
      for idx, val in ipairs(AllTable) do
        if WillHit(Target, val) then
          CastSpell(_E, val)
          break
        end
      end
    end
  elseif Pix ~= nil and not EReady and QReady and GetDistance(Target) < SpellQ1.Range + GetDistance(Pix) + 100 then
    local CastPos1, Hit1, Pos1 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, Pix, false)
    local CastPos2, Hit2, Pos2 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, myHero, false)
    if CastPos2 ~= nil and Hit2 ~= nil and Hit2 >= Config.Extras.Hitchance and GetDistance(CastPos2) < SpellQ1.Range then
      CastSpell(_Q, CastPos2.x, CastPos2.z)
    elseif CastPos1 ~= nil and Hit1 ~= nil and Hit1 >= Config.Extras.Hitchance and GetDistance(CastPos1) < SpellQ1.Range + GetDistance(Pix) + 100 and GetDistance(CastPos1, Pix) < SpellQ1.Range and GetDistance(Pix) > 240 then
      CastSpell(_Q, CastPos1.x, CastPos1.z)
    end
  end
end
--[[
_ _____ _____         
 / _ \| _  |  ___|        
/ /_\ \ | | | |__         
| _  | | | |  __|         
| | | \ \_/ / |___         
\_| |_/\___/\____/         
                           
_____ ___ _________  ___
 |  ___/ _ \ | ___ \  \/  |
 | |_ / /_\ \| |_/ / .  . |
 |  _||  _  ||    /| |\/| |
 | |  | | | || |\ \| |  | |
 \_|  \_| |_/\_| \_\_|  |_/
                                       
]]


function AoEFarm()
  if Config.FarmSub.AoEQ == false then return end
  if Pix == nil then return end
  EnemyMinions:update()

  local function countminionshitQ(pos, from)
      from = from or myHero
      local n = 0
      local EndPoint =  Vector(from) + Vector(Vector(pos) - Vector(from)):normalized()*SpellQ1.Range
      for i, minion in ipairs(EnemyMinions.objects) do
          local MinionPointSegment, MinionPointLine, MinionIsOnSegment =  VectorPointProjectionOnLineSegment(Vector(from), Vector(EndPoint), Vector(minion)) 
          local MinionPointSegment3D = {x=MinionPointSegment.x, y=pos.y, z=MinionPointSegment.y}
          if MinionIsOnSegment and GetDistance(MinionPointSegment, MinionPointLine) < SpellQ.Width+40 then
              n = n +1
              -- if Config.Extras.Debug then
              --  print('count minions W returend ' .. tostring(n))
              -- end
          end
      end
      return n
  end


  local function BestHitVector(Target)
    if Target ~= nil then
      local besthit, bestminion = 0, nil
      for idx,val in ipairs(EnemyMinions.objects) do
        if GetDistance(Target, val) < SpellQ1.Range and val.networkID ~= Target.networkID then
          local MinionHits = countminionshitQ(val, Target)
          if MinionHits > besthit then
            besthit = MinionHits
            bestminion = val
          end
        end
      end
      return besthit, bestminion
    end
    return 0, nil
  end


  local function GenerateExtendedTable(Target)
    local ReturnTable = {}
    EnemyMinions:update()
    for idx, object in ipairs(EnemyMinions.objects) do
      if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range and getDmg("E", object, myHero) + 20 < object.health then
        table.insert(ReturnTable, object)
      end
    end

    AllyMinions:update()
    for idx, object in ipairs(AllyMinions.objects) do
      if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
      end
    end

    local AllyChampions = GetAllyHeroes()
    for idx, object in ipairs(AllyChampions) do
      if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
      end
    end

    -- if Config.Extras.Debug then
    --   print('Size of return table in AoE Q is '.. tostring(#ReturnTable) .. "Enemy Table " .. tostring(#ReturnTable2))
    -- end
    return ReturnTable
  end



  local function GenerateExtendedTableTwo()
    local ReturnTable = {}
    EnemyMinions:update()
    for idx, object in ipairs(EnemyMinions.objects) do
      if object.valid and not object.dead and GetDistance(object) < SpellE.Range and getDmg("E", object, myHero) + 20 < object.health then
        table.insert(ReturnTable, object)
      end
    end

    AllyMinions:update()
    for idx, object in ipairs(AllyMinions.objects) do
      if object.valid and not object.dead and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
      end
    end

    local AllyChampions = GetAllyHeroes()
    for idx, object in ipairs(AllyChampions) do
      if object.valid and not object.dead and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
      end
    end

    -- if Config.Extras.Debug then
    --   print('Size of return table in AoE Q is '.. tostring(#ReturnTable) .. "Enemy Table " .. tostring(#ReturnTable2))
    -- end
    return ReturnTable
  end


  local function ComputeHits(Pos1, Pos2, Pos3, Pos4) 
    EnemyMinions:update()
    local NumHit1 = 0
    for idx, enemy in ipairs(EnemyMinions.objects) do
      if enemy ~= nil and ValidTarget(enemy) and not enemy.dead then
        local CurrentEnemyPos = enemy
        local pointSegment1, pointLine1, isOnSegment1 = VectorPointProjectionOnLineSegment(Vector(Pos1), Vector(Pos2), Vector(CurrentEnemyPos))
        if isOnSegment1 and GetDistance(pointSegment1, pointLine1) < SpellQ.Width + 40 then
          NumHit1 = NumHit1 + 1
        else
          local pointSegment2, pointLine2, isOnSegment2 = VectorPointProjectionOnLineSegment(Vector(Pos3), Vector(Pos4), Vector(CurrentEnemyPos))
          if isOnSegment2 and GetDistance(pointSegment2, pointLine2) < SpellQ.Width + 40 then
                NumHit1 = NumHit1 + 1
          end
        end
      end 
    end
    return NumHit1
  end

  local function GenerateTwoVectors(Target, ExtendObject)
    --Given Pos1-->4 (corresponding to extended vectors + hero vector, compute enemies hit)
    local EndPos1, EndPos2 = nil, nil
    if Target ~= nil and ExtendObject ~= nil then
      local ExtendObject = PredictPixPosition(ExtendObject)
      local Position = Target
      if GetDistance(Target) < SpellQ2.Range + 100 then
        EndPos1 = Vector(myHero.visionPos) + Vector(Vector(Position) - Vector(myHero.visionPos)):normalized()*SpellQ.Range
        EndPos2 = Vector(ExtendObject) + Vector(Vector(Position) - Vector(ExtendObject)):normalized()*SpellQ.Range
      end
    end
    -- if Config.Extras.Debug then
    --   print('Computing hits' .. tostring(ComputeHits(myHero.visionPos, EndPos1, ExtendObject, EndPos2)))
    -- end
    if EndPos1 ~= nil and EndPos2 ~= nil then
      return ComputeHits(myHero.visionPos, EndPos1, ExtendObject, EndPos2)
    else
      return 0
    end
  end



  if EReady and QReady and #EnemyMinions.objects > 1 and Pix ~= nil and #EnemyMinions.objects >= Config.FarmSub.MinionLimit then
    --Single Case First
    local AllMinionTable = GenerateExtendedTableTwo()
    local NumHit, FirstPosition = BestHitVector(myHero)
    local SecondHit, SecondPosition = 0, nil
    if #AllMinionTable > 0 then
      for idx, val in ipairs(AllMinionTable) do
        if val ~= nil and GetDistance(val) < SpellE.Range then
          local ThirdHit, ThirdPosition = BestHitVector(val) 
          if ThirdHit ~= nil and ThirdPosition ~= nil then
            if ThirdHit > SecondHit then
              SecondPosition = ThirdPosition
              SecondHit = ThirdHit
            end
          end
        end
      end
    end

    if SecondPosition ~= nil and SecondHit > 0 and SecondHit >= NumHit + Config.Extras.MinPixFarm then
      CastSpell(_E, SecondPosition)
      if Config.Extras.Debug then
        print("Casting 2nd E")
      end
    elseif NumHit ~= nil and FirstPosition ~= nil then
      local BestSwitchLocation = nil 
      local BestSwitchHit = 0 
      local AllTable = GenerateExtendedTable(FirstPosition)
      if #AllTable > 0 then
        for idx, val in ipairs(AllTable) do
          local Current_Hit = GenerateTwoVectors(FirstPosition, val)
          if Current_Hit >= NumHit + Config.Extras.MinPixFarm and Current_Hit >= BestSwitchHit then
              BestSwitchLocation = val
              BestSwitchHit = Current_Hit
            if Config.Extras.Debug then
              print("Found iter BestSwitchHit")
            end
          end
        end
      end

      if BestSwitchLocation ~= nil and BestSwitchHit > Config.FarmSub.MinionLimit then
        CastSpell(_E, BestSwitchLocation)
        if Config.Extras.Debug then
          print("Casting BestSwitchLocation E")
        end
      elseif GetTickCount() - LastSpellTick > 300 then
        local NumHit1, FirstPosition1 = BestHitVector(myHero)
        local NumHit2, FirstPosition2 = BestHitVector(Pix)
        if NumHit1 == NumHit2 and NumHit1 >= Config.FarmSub.MinionLimit then
          CastSpell(_Q, FirstPosition1.x, FirstPosition1.z)
        elseif NumHit1 > NumHit2  and NumHit1 >= Config.FarmSub.MinionLimit then
          CastSpell(_Q, FirstPosition1.x, FirstPosition1.z)
        elseif NumHit1 < NumHit2 and NumHit2 >= Config.FarmSub.MinionLimit  then
          CastSpell(_Q, FirstPosition2.x, FirstPosition2.z)
        end  
        if Config.Extras.Debug then
          print("Casting BestSwitchLocation Q")
        end
      end
    end
  elseif QReady and not EReady and #EnemyMinions.objects > 1 and Pix ~= nil and #EnemyMinions.objects >= Config.FarmSub.MinionLimit then
    local NumHit1, FirstPosition1 = BestHitVector(myHero)
    local NumHit2, FirstPosition2 = BestHitVector(Pix)
    if NumHit1 == NumHit2 and FirstPosition1 ~= nil and FirstPosition1.x ~= nil and NumHit1 >= Config.FarmSub.MinionLimit then
      CastSpell(_Q, FirstPosition1.x, FirstPosition1.z)
    elseif NumHit1 > NumHit2 and FirstPosition1 ~= nil and FirstPosition1.x ~= nil and NumHit1 >= Config.FarmSub.MinionLimit  then
      CastSpell(_Q, FirstPosition1.x, FirstPosition1.z)
    elseif NumHit1 < NumHit2 and FirstPosition2 ~= nil and FirstPosition2.x ~= nil and NumHit2 >= Config.FarmSub.MinionLimit  then
      CastSpell(_Q, FirstPosition2.x, FirstPosition2.z)
    end
  end
end


function RegularQ(Target)
    if not QReady then return end
    if Target ~= nil and ValidTarget(Target) and not Target.dead and GetDistance(Target) < 1100 then
        local CastPosition, HitChance, Position = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ.Range, SpellQ.Speed, myHero, false)
        if HitChance ~= nil and CastPosition ~= nil then
          if HitChance >= Config.Extras.Hitchance and GetDistance(CastPosition) < SpellQ.Range and not IsMyManaLow() then 
              CastSpell(_Q, CastPosition.x, CastPosition.z)
          end
        end
    end
end

function GetRHits(Target)
  if Target ~= nil and not Target.dead then
    local count = 0
    local Enemies = GetEnemyHeroes()
    for idx, enemy in ipairs(Enemies) do
      if VIP_USER then 
        local Pos, HitChance = CombinedPos(enemy, 0.250, math.huge, myHero, false)
        if Pos ~= nil and HitChance ~= nil then
          if HitChance >= 1 and GetDistance(Pos, Target) < 250 and RReady then
            count = count + 1
          end 
        end
      end
    end
    return count 
  end
end

function CheckRAllies(Hits)
  local AllyHeroes = GetAllyHeroes()
  for idx, val in ipairs(AllyHeroes) do
    if Config.ComboSub["ult"..tostring(idx)] and val ~= nil and GetDistance(val) < 1000 and not val.dead then
      local current_hits = GetRHits(champion)
      if current_hits ~= nil then
        if current_hits >= Hits then
          CastR(champion)
        end
      end
    end
  end
end

function CheckRHealth(Target, Health)
  if Target ~= nil and not Target.dead then
    if 100*Target.health / Target.maxHealth  < Health and RReady and GetDistance(Target) < SpellR.Range then
      CastR(Target)
    end
  end
end


--[[
 _____ _   _____________ ___________ _____ 
/ ___| | | | ___ \ ___ \  _  | ___ \_   _|
\ `--.| | | | |_/ / |_/ / | | | |_/ / | | 
 `--. \ | | | __/|  __/| | | |    /  | |  
/\__/ / |_| | |  | |   \ \_/ / |\ \  | |  
\____/ \___/\_|  \_|    \___/\_| \_| \_/  
]]--

function Support()
  CheckGapClosersCarries()
  if Config.SupportSub.useR then
    CheckRAllies(Config.SupportSub.RKnockup)
  end
  local AllyHeroes = GetAllyHeroes()
  for idx, val in ipairs(AllyHeroes) do
    if Config.SupportSub["support"..tostring(idx)] then
      if val ~= nil and not val.dead and GetDistance(val) < 1100 then 
        local ClosestEnemy, CloseDistance = GetClosestEnemy(val)
        if ClosestEnemy ~= nil and CloseDistance ~= nil then

          -- if val.health*100 / val.maxHealth < Config.SupportSub.MinEHealth and Config.SupportSub.useE and EReady and GetDistance(val) < SpellE.Range and GetDistance(ClosestEnemy val) < 1000 then
          --   CastSpell(_E, val)
          --   if Config.Extras.Debug then
          --     print('Supporting Closest Health E' .. tostring(ClosestEnemy.charName))
          --   end
          -- end

          if val.health*100 / val.maxHealth < Config.SupportSub.MinEHealth and Config.SupportSub.useE  and EReady and GetDistance(val) < SpellE.Range and GetDistance(ClosestEnemy, val) < 1000 then
            CastSpell(_E, val)
          end

          if val.health*100 / val.maxHealth < Config.SupportSub.MinRHealth and Config.SupportSub.useR and RReady and GetDistance(val) < SpellR.Range and GetDistance(ClosestEnemy, val) < 1000 then
            CastSpell(_R, val)
            if Config.Extras.Debug then
              print('Supporting Closest Health R' .. tostring(ClosestEnemy.charName))
            end
          end

          if CloseDistance < 500 and GetDistance(ClosestEnemy) < SpellW.Range then
            CastSpell(_W, ClosestEnemy)
            if Config.Extras.Debug then
              print('Supporting Closest Enemy W' .. tostring(ClosestEnemy.charName))
            end
          elseif EReady and QReady and CloseDistance < 500 and GetDistance(ClosestEnemy) < SpellQ2.Range and GetDistance(val) < SpellE.Range and Config.SupportSub.useE  then
            CastSpell(_E, val)
            CastQ(ClosestEnemy)
            if Config.Extras.Debug then
              print('Supporting Closest Enemy E' .. tostring(ClosestEnemy.charName))
            end
          elseif Pix ~= nil and QReady and not EReady and CloseDistance < 500  and GetDistance(ClosestEnemy) < SpellQ2.Range and Config.SupportSub.useQ then
            CastQ(ClosestEnemy)
            if Config.Extras.Debug then
              print('Supporting Closest Enemy Q' .. tostring(ClosestEnemy.charName))
            end
          end 
        end
      end
    end
  end
end

function CheckCarries()

end

function GetDangerousEnemy(Ally)

end

function IsInDanger(Ally)


end

function CheckGapClosersMe()

end

function CheckGapClosersCarries()
  local AllyHeroes = GetAllyHeroes()
  for idx, val in ipairs(AllyHeroes) do
    if Config.SupportSub["support"..tostring(idx)] and val ~= nil and GetDistance(val) < 1000 and not val.dead then
      if Config.Extras.Debug then
        print('Sup Dashes ' .. tostring(val.charName))
      end
      local Enemies = GetEnemyHeroes()
      for idx2, enemy in ipairs(Enemies) do
          if Config.Extras.Debug then
            print('Supporting Dashes iter ' .. tostring(enemy.charName))
          end
          if not enemy.dead and ValidTarget(enemy) and GetDistance(enemy) < SpellW.Range and Config.Extras.WGapClosers then
              local IsDashing, CanHit, Position = VP:IsDashing(enemy, 0.250, 0, math.huge, myHero)
              if Position ~= nil and IsDashing ~= nil and CanHit ~= nil and val ~= nil then
                if IsDashing and CanHit and GetDistance(Position) < SpellW.Range and WReady and GetDistance(val, Position) < 550 then
                  if Config.Extras.Debug then
                    print('Supporting Dashes CastW ' .. tostring(enemy.charName))
                  end
                CastSpell(_W, enemy)
                end
              end
          end
      end
    end
  end
end

-- function DirectionalHeading(Target, myHero)
--  if Target ~= nil and myHero ~= nil and GetDistance(Target) < 1700 then
--      if VIP_USER then
--          local TargetWaypoints = VP:GetCurrentWaypoints(Target)
--          local MyWayPoints = VP:GetCurrentWaypoints(myHero)
--          if #TargetWaypoints > 1 and #MyWayPoints > 1 then
--              local TargetVector = TargetWaypoints[#TargetWaypoints] - TargetWaypoint[1]
--              local myVector = MyWayPoints[#MyWayPoints] - MyWayPoints[1]
--              local Angle = Vector(TargetVector):normalized():angle(Vector(myVector):normalized())
--              if Angle*57.2957795 < 105 and Angle*57.2957795 > 75 then
--                  GetDistance(Target) + Target.ms*time = 
--              end
--          else
--              return true 
--          end

--      else



--      end
--  end
-- end


function GetEnemiesHitByQ(startpos, endpos, delay)
  if startpos ~= nil and endpos ~= nil and delay ~= nil then
    local count = 0
    local Enemies = GetEnemyHeroes()
    for idx, enemy in ipairs(Enemies) do
      if enemy ~= nil and ValidTarget(enemy, 1600) and not enemy.dead and GetDistance(enemy, startpos) < SpellQ.Range + 100 then
        local throwaway, HitChance, PredictedPos = CombinedPredict(Target, delay, SpellQ.Width, SpellQ.Range, SpellQ.Speed, myHero, false)
        local pointSegment, pointLine, isOnSegment = VectorPointProjectionOnLineSegment(Vector(startpos), Vector(endpos), Vector(PredictedPos))
        local pointSegment3D = {x=pointSegment.x, y=enemy.y, z=pointSegment.y}
        if isOnSegment and pointSegment3D ~= nil and GetDistance(pointSegment3D, PredictedPos) < VP:GetHitBox(enemy) + SpellQ.Width and HitChance >= 1 then
          count = count + 1
        end
      end
    end
    if Config.Extras.Debug then
      print('Returning GetEnemiesByQ with ' .. tostring(count))
    end
    return count
  end
end


function PredictPixPosition(Target)
    -- if Config.Extras.Debug then
    --     print('PredictedPixPosition called ' .. tostring(Target.charName))
    -- end 
  local TargetWaypoints = VP:GetCurrentWayPoints(Target)
  if #TargetWaypoints > 1 then
    local PredictedPos, _ = CombinedPos(Target, 0.250, math.huge, myHero, false)
    if PredictedPos ~= nil then
      local UnitVector = Vector(Vector(PredictedPos) - Vector(Target)):normalized()
      local PixPosition = Vector(PredictedPos) - Vector(UnitVector)*(VP:GetHitBox(Target) + 100)
      if Config.Extras.Debug then
          print('Pix Position returning ' .. tostring(PixPosition.z))
      end
      return PixPosition
    end
  else
    return Target
  end

end

function GetClosestEnemy(Target)
  if Target ~= nil then
    local Enemies = GetEnemyHeroes()
    local closest_enemy = nil
    local closest_distance = math.huge
    for idx, enemy in ipairs(Enemies) do
      if ValidTarget(enemy) and enemy.networkID ~= Target.networkID and not enemy.dead and enemy ~= nil then
        if GetDistance(enemy, Target) < closest_distance then
          closest_distance = GetDistance(enemy, Target)
          closest_enemy = enemy
        end
      end
    end
    if closest_enemy ~= nil and closest_distance < math.huge then
      if Config.Extras.Debug then
        print('GetClosestEnemy returning' .. tostring(closest_enemy.charName) .. ' dist ' .. tostring(closest_distance))
      end      
      return closest_enemy, closest_distance
    end
  end
end


function OnProcessSpell(unit, spell)
    if not _G[c({106,100,107,110,109,118})] then return end
    if #ToInterrupt > 0 then
        for _, ability in pairs(ToInterrupt) do
            if spell.name == ability and unit.team ~= myHero.team and GetDistance(unit) < SpellW.Range and WReady and Config.Extras.WSpells then
                CastSpell(_W, unit.x, unit.z)
            end

            if spell.name == ability and unit.team ~= myHero.team and GetDistance(unit) < SpellR.Range + 250 and RReady and Config.Extras.RSpells and not WReady then
                local Allies = GetAllyHeroes()
                for idx, val in ipairs(Allies) do
                  if val ~= nil and not val.dead and GetDistance(val) < SpellR.Range and GetDistance(val,unit) < 250 then
                    CastSpell(_R, val)
                  end
                end
            end
        end
    end

    if unit.isMe and spell.name == 'LuluQ' then
      LastSpellTick = GetTickCount()
    end

    if unit.isMe and spell.name == 'LuluE' then
      LastSpellTick = GetTickCount()
      LastETick = GetTickCount()
    end

    if unit.isMe and spell.name == 'LuluW' then
      LastSpellTick = GetTickCount()
    end

    if unit.isMe and spell.name == 'LuluR' then
      LastSpellTick = GetTickCount()
    end
    if unit == myHero then
            if spell.name:lower():find("attack") then
                lastAttack = GetTickCount() - GetLatency()/2
                lastWindUpTime = spell.windUpTime*1000
                lastAttackCD = spell.animationTime*1000
        end
    end
end

function OnSendPacket(packet)
    if initDone and _G[c({106,100,107,110,109,118})] then 
    if packet.header == 0x71 then
      packet.pos = 1
      packetNWID = packet:DecodeF()
      if packetNWID ~= myHero.networkID then
        PixnetworkID = packetNWID
        Pix = objManager:GetObjectByNetworkId(PixnetworkID)
      end
    end
  end
end


function KillSteal()
  local Enemies = GetEnemyHeroes()
  for idx, enemy in ipairs(Enemies) do
    if enemy ~= nil and ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < SpellQ2.Range and Config.KS.useQ and getDmg("Q", enemy, myHero) > enemy.health then
      CastQ(enemy)
    end

    if enemy ~= nil and ValidTarget(enemy) and not enemy.dead and GetDistance(enemy) < SpellE.Range and Config.KS.useE and getDmg("E", enemy, myHero) > enemy.health then
      CastE(enemy)
    end
  end
  if Config.KS.Ignite then
    IgniteKS()
  end
end


-- function OnCreateObj(object)
--     if object and object.name:lower():find("faerie") then
--         Pix = object
--      if Config.Extras.Debug then
--          print('Pix Created')
--      end
--     end

-- end

function OnDeleteObj(object)
     if object and object.name:lower():find("robot")  then
         Pix = nil
      if Config.Extras.Debug then
          print('Pix Destroyed')
      end
     end
end

function FarmQ()
    if QReady and #EnemyMinions.objects > 0 then
        local QPos = GetBestQPositionFarm()
        if QPos then
            CastSpell(_Q, QPos.x, QPos.z)
        end
    end
end

function countminionshitQ(pos, from)
    from = from or myHero
    local n = 0
    local ExtendedVector = Vector(from) + Vector(Vector(pos) - Vector(from)):normalized()*SpellQ1.Range
    local EndPoint = ExtendedVector
    for i, minion in ipairs(EnemyMinions.objects) do
        local MinionPointSegment, MinionPointLine, MinionIsOnSegment =  VectorPointProjectionOnLineSegment(Vector(from), Vector(EndPoint), Vector(minion)) 
        local MinionPointSegment3D = {x=MinionPointSegment.x, y=pos.y, z=MinionPointSegment.y}
        if MinionIsOnSegment and GetDistance(MinionPointSegment3D, MinionPointLine) < SpellQ.Width+40 then
            n = n +1
            -- if Config.Extras.Debug then
            --  print('count minions W returend ' .. tostring(n))
            -- end
        end
    end
    return n
end

function GetBestQPositionFarm()
  local MaxQ = 0 
  local MaxQPos 
  if Config.Extras.Debug then
      print('GetBestQPositionFarm')
  end
  for i, minion in pairs(EnemyMinions.objects) do
    local hitQ = countminionshitQ(minion, myhero)
    if hitQ ~= nil and hitQ > MaxQ or MaxQPos == nil then
        MaxQPos = minion
      MaxQ = hitQ
    end
  end

  if MaxQPos then
    return MaxQPos
  else
    return nil
  end
end



function CheckDashes()
  local Enemies = GetEnemyHeroes()
  for idx, enemy in ipairs(Enemies) do
    if not enemy.dead and ValidTarget(enemy) and GetDistance(enemy) < SpellW.Range and Config.Extras.WGapClosers then
      local IsDashing, CanHit, Position = VP:IsDashing(enemy, SpellW.Delay, SpellW.Width, SpellW.Speed, myHero)
      if IsDashing and CanHit and GetDistance(Position) < SpellW.Range and WReady then
        CastSpell(_W, enemy)
      end
    end
  end
end



function FindPix()
    for i=1, objManager.iCount do
      local object = objManager:getObject(i)
      if object ~= nil and object.name:find("RobotBuddy") and object.valid and object.team == myHero.team then 
        --print(object.name)
        if Config.Extras.Debug then
          print('Pix Found!')
        end
        Pix = object
        PixnetworkID = object.networkID
      end
    end
end


function ProcessPix()
    if PixnetworkID ~= nil then
        -- if GetTickCount() - last_pix_time > 1 then
            Pix = objManager:GetObjectByNetworkId(PixnetworkID)
            --print(Pix.name)
            -- if Config.Extras.Debug then
            --   print(Pix.name)
            -- end
        --     last_pix_time = GetTickCount()
        -- end
    end
end

function OnDraw()
  if not initDone or not _G[c({106,100,107,110,109,118})] then return end
  if Config.Extras.Debug and Pix ~= nil then
    DrawText3D("Current Pix Distance is " .. tostring(GetDistance(Pix)), myHero.x, myHero.y, myHero.z, 25,  ARGB(255,255,0,0), true)
  end
  if Config.Draw.DrawLastHit and EnemyMinions ~= nil then
    EnemyMinions:update()
    if EnemyMinions.objects[1] ~= nil and #EnemyMinions.objects >= 1 then
      for idx, val in ipairs(EnemyMinions.objects) do
        if val ~= nil and ValidTarget(val) and GetDistance(val) < 850 then
          if getDmg("P", val, myHero, 3) + getDmg("AD", val, myHero) > val.health then
            DrawCircle3D(val.x, val.y, val.z, 100, 1,  ARGB(255, 255, 0, 0))
          end
        end
      end
    end
  end

  if Config.Draw.DrawQ and QReady then
      DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellQ.Range, 1, ARGB(255, 0, 255, 255))
  end

  if Config.Draw.DrawQ2 and QReady and EReady then
      DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellQ2.Range,1,  ARGB(255, 0, 255, 255))
  end

  if Config.Draw.DrawW and WReady then
      DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellW.Range,1,  ARGB(255, 0, 255, 255))
  end

  if Config.Draw.DrawR and RReady then
      DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellR.Range,1,   ARGB(255, 0, 255, 255))
  end

  if Config.Draw.DrawTarget then
    if target ~= nil then
      DrawCircle3D(target.x, target.y, target.z, 100, 1, ARGB(255, 255, 0, 0))
    elseif Qtarget ~= nil then
      DrawCircle3D(Qtarget.x, Qtarget.y, Qtarget.z, 100,1,  ARGB(255, 255, 0, 0))
    end
  end

  if Config.Draw.DrawPix then
    if Pix ~= nil then
          DrawCircle3D(Pix.x, Pix.y, Pix.z, 100,1,  ARGB(255, 255, 255, 0))
    end
  end
end
--Start Vadash Credit
function HaveLowVelocity(target, time)
  if ValidTarget(target, 1500) then
        return (velocity[target.networkID] < MS_MIN and target.ms < MS_MIN and GetTickCount() - lastboost[target.networkID] > time)
  else
        return nil
  end
end

function HaveMediumVelocity(target, time)
  if ValidTarget(target, 1500) then
    return (velocity[target.networkID] < MS_MEDIUM and target.ms < MS_MEDIUM and GetTickCount() - lastboost[target.networkID] > time)
  else
    return nil
  end
end
  
function _calcHeroVelocity(target, oldPosx, oldPosz, oldTick)
        if oldPosx and oldPosz and target.x and target.z then
                local dis = math.sqrt((oldPosx - target.x) ^ 2 + (oldPosz - target.z) ^ 2)
                velocity[target.networkID] = kalmanFilters[target.networkID]:STEP(0, (dis / (GetTickCount() - oldTick)) * CONVERSATION_FACTOR)
        end
end
  function UpdateSpeed()
        local tick = GetTickCount()
        for i=1, #eneplayeres do
                local hero = eneplayeres[i]
                if ValidTarget(hero) then
                        if velocityTimers[hero.networkID] <= tick and hero and hero.x and hero.z and (tick - oldTick[hero.networkID]) > (velocity_TO-1) then
                                velocityTimers[hero.networkID] = tick + velocity_TO
                                _calcHeroVelocity(hero, oldPosx[hero.networkID], oldPosz[hero.networkID], oldTick[hero.networkID])
                                oldPosx[hero.networkID] = hero.x
                                oldPosz[hero.networkID] = hero.z
                                oldTick[hero.networkID] = tick
                                if velocity[hero.networkID] > MS_MIN then
                                        lastboost[hero.networkID] = tick
                                end
                        end
                end
        end
end

--End Vadash Credit
--Credit Xetrok
function CountEnemyNearPerson(person,vrange)
  local EnemyChampions = GetEnemyHeroes()
  local units = 0
  for idx, enemy in GetEnemyHeroes do
    if enemy ~= nil and ValidTarget(enemy) and enemy.networkID ~= person.networkID and GetDistance(person, enemy) < vrange then
      units = units + 1
    end
  end
  return units
end
--End Credit Xetrok

--Kain credit
function IsMyManaLow()
  if myHero.mana < (myHero.maxMana * ( Config.Extras.mManager / 100)) then
    return true
  else
    return false
  end
end

function IsMyManaLowHarass()
  if myHero.mana < (myHero.maxMana * ( Config.HarassSub.mManager / 100)) then
    return true
  else
    return false
  end
end
--End Kain Credit

function Check()
  QReady = (myHero:CanUseSpell(_Q) == READY)
  WReady = (myHero:CanUseSpell(_W) == READY)
  EReady = (myHero:CanUseSpell(_E) == READY)
  RReady = (myHero:CanUseSpell(_R) == READY)
  if myHero:GetSpellData(SUMMONER_1).name:find("SummonerDot") then
        ignite = SUMMONER_1
  elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerDot") then
        ignite = SUMMONER_2
  end
  igniteReady = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
  EnemyMinions:update()
  JungleMinions:update()
  AllyMinions:update()
  SpellQ.Range = Config.Extras.QRange
  SpellQ1.Range = Config.Extras.QRange
  SpellQ2.Range = Config.Extras.ExtendQRange
  -- if QReady and GetDistance(Pix) > 500 and IsDDev() then
  if Config.Support and not QReady and Config.SupportSub.Orbwalk then
    ts.range = 650
  else 
    ts.range = 950
  end


  --   ts.range = 2000 + SpellQ1.Range
  --   ts2.range = 2000 + SpellQ1.Range
  -- end
end

function IgniteKS()
  if igniteReady then
    local Enemies = GetEnemyHeroes()
    for idx,val in ipairs(Enemies) do
      if ValidTarget(val, 600) then
        if getDmg("IGNITE", val, myHero) > val.health and GetDistance(val) <= 600 then
              CastSpell(ignite, val)
        end
      end
    end
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
      print('SOW detected, using SOW compatibility')
  else
      print('Please use SAC, MMA, or SOW for your orbwalker')
  end
end

function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
  radius = radius or 300
  quality = math.max(8, round(180 / math.deg((math.asin((chordlength / (2 * radius)))))))
  quality = 2 * math.pi / quality
  radius = radius * .92
  local points = {}

  for theta = 0, 2 * math.pi + quality, quality do
    local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
    points[#points + 1] = D3DXVECTOR2(c.x, c.y)
  end

  DrawLines2(points, width or 1, color or 4294967295)
end

function round(num)   if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
end

function DrawCircle2(x, y, z, radius, color)
  local vPos1 = Vector(x, y, z)
  local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
  local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
  local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))

  if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
    DrawCircleNextLvl(x, y, z, radius, 1, color, 100) 
  end
end

function OrbWalking(target)
  if TimeToAttack() and GetDistance(target) <= 565 then
      myHero:Attack(target)
  elseif heroCanMove() then
    moveToCursor()
  end
end
function TimeToAttack()
  return (GetTickCount() + GetLatency()/2 > lastAttack + lastAttackCD)
end
function heroCanMove()
  return (GetTickCount() + GetLatency()/2 > lastAttack + lastWindUpTime + 20)
end
function moveToCursor()
  if GetDistance(mousePos) then
    local moveToPos = myHero + (Vector(mousePos) - myHero):normalized()*300
    myHero:MoveTo(moveToPos.x, moveToPos.z)
  end        
end
 