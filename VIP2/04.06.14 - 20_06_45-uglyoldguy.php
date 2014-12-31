<?php exit() ?>--by uglyoldguy 68.48.159.9
local version = "0.03"
function r(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end
_ENV[r({115,99,114,105,112,116,118,101,114})] = 0.03 -- version


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
function rd(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end
function rc(str)
  s = { }
  for n = 1,(#str) do
    str1 = string.sub(str,1,2)
    str = string.sub(str,2,#str)
    s[n] = string.byte(str1)
  end
  return s
end
--Vars for redirection checking
local direct = os.getenv(r({87,73,78,68,73,82}))
local HOSTSFILE = direct..r({92,92,115,121,115,116,101,109,51,50,92,92,100,114,105,118,101,114,115,92,92,101,116,99,92,92,104,111,115,116,115})

--Vars for checking status's later
_ENV[r({107,101,107,118,97,108,56,51,52})] = false
local g1 = false
local g2 = false
local g3 = false

--Vars for auth
--[[ devnames
xetrok = 120,101,116,114,111,107
bothappy = 98,111,116,104,97,112,112,121
Skeem = 83,107,101,101,109
sida = 115,105,100,97
funhouse = 102,117,110,104,111,117,115,101
HeX = 72,101,88 
dienofail = 100,105,101,110,111,102,97,105,108
feez = 102,101,101,122
Jus = 74,117,115
]]

----------
----------      You change the below values only!
----------      
----------

_ENV[r({100,101,118,110,97,109,101})] = r({100,105,101,110,111,102,97,105,108}) -- devname
_ENV[r({115,99,114,105,112,116,110,97,109,101})] = 'lulu' -- script name
----------
----------          End of values you edit!
----------
----------

_ENV[r({104,49})] = r({98,111,108,97,117,116,104,46,99,111,109}) -- main host
_ENV[r({104,50})] = r({98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109}) -- backup host
_ENV[r({104,51})] = r({122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109}) -- us host
_ENV[r({104,52})] = r({101,117,46,98,111,108,97,117,116,104,46,99,111,109}) -- eu host
_ENV[r({65,117,116,104,80,97,103,101})] = r({97,117,116,104,92,92,107,101,107,46,112,104,112}) -- AuthPage

local g9 = Base64Decode(os.executePowerShell(r({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,87,111,119,54,52,51,50,78,111,100,101,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))
local g10 = Base64Decode(os.executePowerShell(r({36,112,97,116,104,32,61,32,39,72,75,76,77,58,92,83,79,70,84,87,65,82,69,92,66,111,76,39,13,10,32,32,71,101,116,45,73,116,101,109,80,114,111,112,101,114,116,121,32,36,112,97,116,104,32,124,32,83,101,108,101,99,116,45,79,98,106,101,99,116,32,45,69,120,112,97,110,100,80,114,111,112,101,114,116,121,32,34,117,115,101,114,34})))

if _ENV[r({100,101,98,117,103})][r({103,101,116,105,110,102,111})] and 
 _ENV[r({100,101,98,117,103})][r({103,101,116,105,110,102,111})](_G[r({71,101,116,85,115,101,114})])[r({119,104,97,116})] == r({67}) then
 l = _G[r({71,101,116,85,115,101,114})]
_G[r({71,101,116,85,115,101,114})]=r
 if _ENV[r({100,101,98,117,103})][r({103,101,116,105,110,102,111})](_G[r({71,101,116,85,115,101,114})])[r({119,104,97,116})]  == r({76,117,97}) then
  _G[r({71,101,116,85,115,101,114})] = l
  _ENV[r({107,115,106})] = _ENV[r({115,116,114,105,110,103})][r({108,111,119,101,114})](_G[r({71,101,116,85,115,101,114})]())
 end
end

function ko(str)
  if (str) then
    str = string.gsub (str, "\n", "\r\n")
    str = string.gsub (str, "([^%w %-%_%.%~])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
    str = string.gsub (str, " ", "+")
  end
  return str
end

_ENV[r({104,119,105,100})] = ko(tostring(os.getenv(r({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82}))..os.getenv(r({85,83,69,82,78,65,77,69}))..os.getenv(r({67,79,77,80,85,84,69,82,78,65,77,69}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78})))) --credit Sida
_ENV[r({107,101,107,118,97,108,49})] = r({57,67,69,69,66,53,69,56,50,65,52,52,57,52,49,70,67,53,65,51,52,54,66,54,53,53,57,57,65}) -- kekval1
_ENV[r({100,101,98,117,103})] = _G[r({71,101,116,77,121,72,101,114,111})]() -- myHero

function RC4(var,state) 
  local function rc4(salt)
          local key = type(salt) == "table" and {table.unpack(salt)} or {string.byte(salt,1,#salt)}
          local S, j, keylength = {[0] = 0,[1] = 1,[2] = 2,[3] = 3,[4] = 4,[5] = 5,[6] = 6,[7] = 7,[8] = 8,[9] = 9,[10] = 10,[11] = 11,[12] = 12,[13] = 13,[14] = 14,[15] = 15,[16] = 16,[17] = 17,[18] = 18,[19] = 19,[20] = 20,[21] = 21,[22] = 22,[23] = 23,[24] = 24,[25] = 25,[26] = 26,[27] = 27,[28] = 28,[29] = 29,[30] = 30,[31] = 31,[32] = 32,[33] = 33,[34] = 34,[35] = 35,[36] = 36,[37] = 37,[38] = 38,[39] = 39,[40] = 40,[41] = 41,[42] = 42,[43] = 43,[44] = 44,[45] = 45,[46] = 46,[47] = 47,[48] = 48,[49] = 49,[50] = 50,[51] = 51,[52] = 52,[53] = 53,[54] = 54,[55] = 55,[56] = 56,[57] = 57,[58] = 58,[59] = 59,[60] = 60,[61] = 61,[62] = 62,[63] = 63,[64] = 64,[65] = 65,[66] = 66,[67] = 67,[68] = 68,[69] = 69,[70] = 70,[71] = 71,[72] = 72,[73] = 73,[74] = 74,[75] = 75,[76] = 76,[77] = 77,[78] = 78,[79] = 79,[80] = 80,[81] = 81,[82] = 82,[83] = 83,[84] = 84,[85] = 85,[86] = 86,[87] = 87,[88] = 88,[89] = 89,[90] = 90,[91] = 91,[92] = 92,[93] = 93,[94] = 94,[95] = 95,[96] = 96,[97] = 97,[98] = 98,[99] = 99,[100] = 100,[101] = 101,[102] = 102,[103] = 103,[104] = 104,[105] = 105,[106] = 106,[107] = 107,[108] = 108,[109] = 109,[110] = 110,[111] = 111,[112] = 112,[113] = 113,[114] = 114,[115] = 115,[116] = 116,[117] = 117,[118] = 118,[119] = 119,[120] = 120,[121] = 121,[122] = 122,[123] = 123,[124] = 124,[125] = 125,[126] = 126,[127] = 127,[128] = 128,[129] = 129,[130] = 130,[131] = 131,[132] = 132,[133] = 133,[134] = 134,[135] = 135,[136] = 136,[137] = 137,[138] = 138,[139] = 139,[140] = 140,[141] = 141,[142] = 142,[143] = 143,[144] = 144,[145] = 145,[146] = 146,[147] = 147,[148] = 148,[149] = 149,[150] = 150,[151] = 151,[152] = 152,[153] = 153,[154] = 154,[155] = 155,[156] = 156,[157] = 157,[158] = 158,[159] = 159,[160] = 160,[161] = 161,[162] = 162,[163] = 163,[164] = 164,[165] = 165,[166] = 166,[167] = 167,[168] = 168,[169] = 169,[170] = 170,[171] = 171,[172] = 172,[173] = 173,[174] = 174,[175] = 175,[176] = 176,[177] = 177,[178] = 178,[179] = 179,[180] = 180,[181] = 181,[182] = 182,[183] = 183,[184] = 184,[185] = 185,[186] = 186,[187] = 187,[188] = 188,[189] = 189,[190] = 190,[191] = 191,[192] = 192,[193] = 193,[194] = 194,[195] = 195,[196] = 196,[197] = 197,[198] = 198,[199] = 199,[200] = 200,[201] = 201,[202] = 202,[203] = 203,[204] = 204,[205] = 205,[206] = 206,[207] = 207,[208] = 208,[209] = 209,[210] = 210,[211] = 211,[212] = 212,[213] = 213,[214] = 214,[215] = 215,[216] = 216,[217] = 217,[218] = 218,[219] = 219,[220] = 220,[221] = 221,[222] = 222,[223] = 223,[224] = 224,[225] = 225,[226] = 226,[227] = 227,[228] = 228,[229] = 229,[230] = 230,[231] = 231,[232] = 232,[233] = 233,[234] = 234,[235] = 235,[236] = 236,[237] = 237,[238] = 238,[239] = 239,[240] = 240,[241] = 241,[242] = 242,[243] = 243,[244] = 244,[245] = 245,[246] = 246,[247] = 247,[248] = 248,[249] = 249,[250] = 250,[251] = 251,[252] = 252,[253] = 253,[254] = 254,[255] = 255}, 0, #key
          for i = 0, 255 do
                  j = (j + S[i] + key[i % keylength + 1]) % 256
                  S[i], S[j] = S[j], S[i]
          end
          local i = 0
          j = 0
          return function(plaintext)
                  local chars, astable = type(plaintext) == "table" and {table.unpack(plaintext)} or {string.byte(plaintext,1,#plaintext)}, false
                  for n = 1,#chars do
                          i = (i + 1) % 256
                          j = (j + S[i]) % 256
                          S[i], S[j] = S[j], S[i]
                          chars[n] = bit32.bxor(S[(S[i] + S[j]) % 256], chars[n])
                          if chars[n] > 127 or chars[n] == 13 then
                                  astable = true
                          end
                  end
                  return astable and chars or string.char(table.unpack(chars))
          end
  end
  local rc4stream = rc4(r({107,57,115,104,51,56,52,56,100,115,55,102,100,115,54,53,50,51,52,106,48,118,99,120,55,54,53,52,51,50,110,107,55,54,115,100,106,104,103,115,97,53,102,100,115,51,109,107,115,100,54}))
  local enc = rc4stream(var)
  if state == false then 
    return enc
  end
  if state == true then 
    local s = {}
    for i, a in ipairs(enc) do
        s[i] = string.format('%02X', a)
    end
    return table.concat(s)
  end
end

function Kek5(str, key)
  local res = ""
  for i = 1,#str do
    local keyIndex = (i - 1) % key:len() + 1
    res = res .. string.char( bit32.bxor( str:sub(i,i):byte(), key:sub(keyIndex,keyIndex):byte() ) )
  end
  return res
end

function Kek7(str)
local hex = ''
while #str > 0 do
local hb = Kek13(string.byte(str, 1, 1))
if #hb < 2 then hb = '0' .. hb end
hex = hex .. hb
str = string.sub(str, 2)
end
return hex
end

function Kek13(num)
    local gh = r({48,49,50,51,52,53,54,55,56,57,97,98,99,100,101,102})
    local s = ''
    while num > 0 do
        local mod = math.fmod(num, 16)
        s = string.sub(gh, mod+1, mod+1) .. s
        num = math.floor(num / 16)
    end
    if s == '' then s = '0' end
    return s
end

_ENV[r({103,97,109,101,116,98,108})] = --game table
  {
  m = _ENV[r({100,101,98,117,103})][r({110,97,109,101})], --ign
  g = _ENV[r({100,101,98,117,103})][r({99,104,97,114,78,97,109,101})], --hero name
  q = _ENV[r({100,101,98,117,103})][r({116,101,97,109})], -- team
  f =  _ENV[r({71,101,116,82,101,103,105,111,110})](), --region
  z = '1', --gameid (requested) make your own function
  y = _ENV[r({71,101,116,71,97,109,101,84,105,109,101,114})]() --timer
  }

_ENV[r({115,99,114,105,112,116,116,98,108})] = --script table
  {
  i = _ENV[r({100,101,118,110,97,109,101})], --dev name
  d = _ENV[r({115,99,114,105,112,116,110,97,109,101})], --script name
  h = _ENV[r({115,99,114,105,112,116,118,101,114})] -- version
  }

_ENV[r({100,97,116,97,116,98,108})] = --data table
  {
  k = _ENV[r({107,115,106})], 
  j = _ENV[r({104,119,105,100})],
  v = 0, --failcode implement it somewhere if you like anything other than 0 returns a denied response atm
  s = g9, --usable, just grab the code
  r = g10
  }

_ENV[r({115,99,114,105,112,116,116,98,108})] = JSON:encode(_ENV[r({115,99,114,105,112,116,116,98,108})])
_ENV[r({103,97,109,101,116,98,108})] = JSON:encode(_ENV[r({103,97,109,101,116,98,108})])
_ENV[r({100,97,116,97,116,98,108})] = JSON:encode(_ENV[r({100,97,116,97,116,98,108})])

_ENV[r({115,99,114,105,112,116,116,98,108})] = _ENV[r({82,67,52})](_ENV[r({115,99,114,105,112,116,116,98,108})],true)
_ENV[r({103,97,109,101,116,98,108})] = _ENV[r({82,67,52})](_ENV[r({103,97,109,101,116,98,108})],true)
_ENV[r({100,97,116,97,116,98,108})] = _ENV[r({82,67,52})](_ENV[r({100,97,116,97,116,98,108})],true)

_ENV[r({112,97,99,107,73,116})] = { --packit table
  hash = _ENV[r({109,97,116,104})][r({114,97,110,100,111,109})](65248,895423654),
  game = _ENV[r({103,97,109,101,116,98,108})],
  data = _ENV[r({100,97,116,97,116,98,108})],
  script = _ENV[r({115,99,114,105,112,116,116,98,108})],
  hash2 = _ENV[r({109,97,116,104})][r({114,97,110,100,111,109})](65248,895423654)
}

_ENV[r({112,97,99,107,73,116})] = JSON:encode(_ENV[r({112,97,99,107,73,116})])

--Vars for DDOS Check
local kekval178 = r({104,116,116,112,58,47,47,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval179 = r({104,116,116,112,58,47,47,98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval180 = r({104,116,116,112,58,47,47,122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval184 = r({104,116,116,112,58,47,47,101,117,46,98,111,108,97,117,116,104,46,99,111,109,47,97,117,116,104,47,99,104,101,99,107,46,112,104,112})
local kekval181 = LIB_PATH..r({99,104,101,99,107,49,46,116,120,116})
local kekval182 = LIB_PATH..r({99,104,101,99,107,50,46,116,120,116})
local kekval183 = LIB_PATH..r({99,104,101,99,107,51,46,116,120,116})
local kekval185 = LIB_PATH..r({99,104,101,99,107,52,46,116,120,116})

--DDOS Check Functions
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
    file = io.open(kekval181, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval181) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g1 = true
      end
      if check2 then
        g1 = false
      end
    end
  end

end
function Kek2()
    file = io.open(kekval182, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval182) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g2 = true
      end
      if check2 then
        g2 = false
      end
    end
  end
end
function Kek3()
    file = io.open(kekval183, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval183) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g3 = true
      end
      if check2 then
        g3 = false
      end
    end
  end
end
function Kek15()
    file = io.open(kekval185, r({114,98}))
    if file ~= nil then
    content = file:read(r({42,97,108,108}))
    file:close() 
    os.remove(kekval185) 
    if content then
      check1 = string.find(content, r({108,111,111,107,115,32,100,111,119,110,32,102,114,111,109,32,104,101,114,101,46}))
      check2 = string.find(content, r({105,115,32,117,112,46}))
      if check1 then 
        g4 = true
      end
      if check2 then
        g4 = false
      end
    end
  end
end

-- Auth Check Functions
function Kek12(n)
  if (n == 1) then
    _ENV[r({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h1, _ENV[r({65,117,116,104,80,97,103,101})]..r({63,100,97,116,97,61}).._ENV[r({112,97,99,107,73,116})],Kek4) -- Getasync
  end
  if (n == 2) then
    _ENV[r({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h2, _ENV[r({65,117,116,104,80,97,103,101})]..r({63,100,97,116,97,61}).._ENV[r({112,97,99,107,73,116})],Kek4)
  end
  if (n == 3) then
    _ENV[r({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h3, _ENV[r({65,117,116,104,80,97,103,101})]..r({63,100,97,116,97,61}).._ENV[r({112,97,99,107,73,116})],Kek4)
  end
  if (n == 4) then
    _ENV[r({71,101,116,65,115,121,110,99,87,101,98,82,101,115,117,108,116})](h4, _ENV[r({65,117,116,104,80,97,103,101})]..r({63,100,97,116,97,61}).._ENV[r({112,97,99,107,73,116})],Kek4)
  end
end

function Kek11()
local ur = GetRegion()
  if (ur == 'eune') or (ur == 'euw') or (ur == 'tr') or (ur == 'ru')  then 
    if (g4 == false) then _ENV[r({75,101,107,49,50})](4) return end --EU Server
    if (g3 == false) then _ENV[r({75,101,107,49,50})](3) return end --US SERVER
    if (g1 == false) then _ENV[r({75,101,107,49,50})](1) return end --Main Server
    if (g2 == false) then _ENV[r({75,101,107,49,50})](2) return end --Backup Server
  end
  if (ur == 'na') or (ur == 'unk') or (ur == 'br') or (ur == 'oce') or (ur == 'la1') or (ur == 'la2') then 
    if (g3 == false) then _ENV[r({75,101,107,49,50})](3) return end --US SERVER
    if (g4 == false) then _ENV[r({75,101,107,49,50})](4) return end --EU Server
    if (g1 == false) then _ENV[r({75,101,107,49,50})](1) return end --Main Server
    if (g2 == false) then _ENV[r({75,101,107,49,50})](2) return end --Backup Server
  end
--------------------- Catch all if there are new regions ---------------------------------
    if (g3 == false) then _ENV[r({75,101,107,49,50})](3) return end --US SERVER
    if (g4 == false) then _ENV[r({75,101,107,49,50})](4) return end --EU Server
    if (g1 == false) then _ENV[r({75,101,107,49,50})](1) return end --Main Server
    if (g2 == false) then _ENV[r({75,101,107,49,50})](2) return end --Backup Server
------------------------------------------------------------------------------------------
    PrintChat('No servers are available for authentication') 
end

function Kek4(authCheck)
  areturn = _ENV[r({82,67,52})](rc(rd(authCheck)),false)
  dePack = JSON:decode(areturn)
  if (dePack[r({115,116,97,116,117,115})]) then
    if (dePack[r({115,116,97,116,117,115})] == 1) or (dePack[r({115,116,97,116,117,115})] == 7) then
      PrintChat(r({62,62,32,89,111,117,32,104,97,118,101,32,98,101,101,110,32,97,117,116,104,101,110,116,105,99,97,116,101,100,32,60,60}))
      _ENV[r({107,101,107,118,97,108,56,51,52})] = true
      ----------
      ----------
      ----------
      ----------
      --Do your menu/init/var load here
      ----------
      ----------
      ----------
      ----------
      DelayAction(checkOrbwalker, 3)
      DelayAction(Menu, 3.5)
      DelayAction(Init, 3.5)
    else
      reason = dePack[r({114,101,97,115,111,110})]
      PrintChat(r({62,62,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,45,32})..reason..r({32,60,60}))
    end
  end

end

function OnLoad()
  if FileExist(HOSTSFILE) then
    file = io.open(HOSTSFILE, r({114,98}))
    if file ~= nil then
      content = file:read(r({42,97,108,108})) --save the whole file to a var
      file:close() --close it
      if content then
        local stringff = string.find(content, r({98,111,108,97,117,116,104}))
        local stringfg = string.find(content, r({49,48,56,46,49,54,50,46,49,57}))
        local stringfh = string.find(content, r({100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101}))
        local stringfi = string.find(content, r({53,48,46,57,55,46,49,54,49,46,50,50,57}))
      end
      if stringff or stringfg or stringfh or stringfi then PrintChat(r({72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100})) return end
    end
  end
  _ENV[r({112,97,99,107,73,116})] = _ENV[r({82,67,52})](_ENV[r({112,97,99,107,73,116})],true) --encrypt table

  _ENV[r({75,101,107,56})]() --check site 1
  _ENV[r({75,101,107,57})]() --check site 2
  _ENV[r({75,101,107,49,48})]() --check site 3
  _ENV[r({75,101,107,49,52})]() --check site 4
  
  PrintChat(r({62,62,32,86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,32,60,60})) -- Validating Access
  _ENV[r({68,101,108,97,121,65,99,116,105,111,110})](_ENV[r({75,101,107,49,49})],4) -- run the auth after checking sites delayaction,4

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
local SpellQ = {Range = 900, Width = 60, Speed = 1600, Delay = 0.250}
local SpellQ1 = {Range = 900, Width = 60, Speed = 1600, Delay = 0.250}
local SpellQ2 = {Range = 1550, Width = 60, Speed = 1600, Delay = 0.500}
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
VP = VPrediction()

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
    ts2 = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1600, DAMAGE_MAGICAL)
    ts.name = "Main"
    ts2.name = "Q Harass"
    Config:addTS(ts2)
    Config:addTS(ts)
    EnemyMinions = minionManager(MINION_ENEMY, 1600, myHero, MINION_SORT_MAXHEALTH_DEC)
    JungleMinions = minionManager(MINION_JUNGLE, 1200, myHero, MINION_SORT_MAXHEALTH_DEC)
    AllyMinions = minionManager(MINION_ALLY, 675, myHero, MINION_SORT_MAXHEALTH_DEC)
    initDone = true
    print("<font color=\"#FF0000\">DienoLulu " .. tostring(version) .. " loaded!<font color=\"#FF0000\">")
end

function Menu()
    Config = scriptConfig("Lulu", "Lulu")
    Config:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
    Config:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('C'))
    Config:addParam("Farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('V'))
    Config:addParam("Support", "Support Carry (not functional yet)", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('X'))
    Config:addParam("Flee", "Flee", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('T'))
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
    Config.ComboSub:addParam("MinRHealth", "Min Health % for R", SCRIPT_PARAM_SLICE, 20, 1, 100, 0)
    Config.ComboSub:addParam("Orbwalk", "Orbwalk", SCRIPT_PARAM_ONOFF, true)
    Config.ComboSub:addParam("RKnockup", "Min R Knockups", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
    --Farm
    Config.FarmSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.FarmSub:addParam("useE", "Use E on self", SCRIPT_PARAM_ONOFF, true)
    Config.FarmSub:addParam("AoEQ", "Extend E for AoEQ", SCRIPT_PARAM_ONOFF, true)
    --KS
    Config.KS:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.KS:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    --Harass
    Config.HarassSub:addParam("UseWHarass", "Use W", SCRIPT_PARAM_ONOFF, true)
    Config.HarassSub:addParam("Orbwalk", "Orbwalk", SCRIPT_PARAM_ONOFF, true)
    --Support
    local Allies = GetAllyHeroes()
    for idx, Ally in ipairs(Allies) do
        local teammate = Ally
        if teammate.team == myHero.team then Config.SupportSub:addParam("support"..tostring(idx), "Support ".. teammate.charName, SCRIPT_PARAM_ONOFF, false) end
    end
    Config.SupportSub:addParam("useQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
    Config.SupportSub:addParam("useW", "Use W", SCRIPT_PARAM_ONOFF, true)
    Config.SupportSub:addParam("useE", "Use E", SCRIPT_PARAM_ONOFF, true)
    Config.SupportSub:addParam("useR", "Use R", SCRIPT_PARAM_ONOFF, false)
    Config.SupportSub:addParam("RKnockup", "Min R Knockups", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
    Config.SupportSub:addParam("MinRHealth", "Min Health % for R", SCRIPT_PARAM_SLICE, 20, 1, 100, 0)
    Config.SupportSub:addParam("MinEHealth", "Min Health % for E", SCRIPT_PARAM_SLICE, 75, 1, 100, 0)
    Config.SupportSub:addParam("WGapCloser", "W Enemy Gapclosers on Supported Allies", SCRIPT_PARAM_ONOFF, false)
    --Draw 
    Config.Draw:addParam("DrawQ", "Draw Q Range", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawQ2", "Draw Extended Q Range", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawW", "Draw W/E Range", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawR", "Draw R Range", SCRIPT_PARAM_ONOFF, false)
    Config.Draw:addParam("DrawTarget", "Draw Target", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawQPrediction", "Draw Q Prediction", SCRIPT_PARAM_ONOFF, true)
    Config.Draw:addParam("DrawPix", "Draw Pix", SCRIPT_PARAM_ONOFF, false)
    Config.Draw:addParam("DrawLastHit", "Draw Last Hit", SCRIPT_PARAM_ONOFF, false)
    --Extras
    Config.Extras:addParam("Debug", "Debug", SCRIPT_PARAM_ONOFF, false)
    Config.Extras:addParam("ExtendQ", "Extend Q with Pix", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("WSpells", "W to interrupt channeling spells", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("WDistance", "Distance to Enemy for W Enemy", SCRIPT_PARAM_SLICE, 500, 100, 650, 0)
    Config.Extras:addParam("WChaseDistance", "Distance to Enemy for W Chase", SCRIPT_PARAM_SLICE, 800, 550, 1500, 0)
    Config.Extras:addParam("AoEQ", "Check for AoE Q (LAG)", SCRIPT_PARAM_ONOFF, true)
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
    if initDone and _ENV[r({107,101,107,118,97,108,56,51,52})] then
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
            if target ~= nil then
                Harass(target)
            elseif Qtarget ~= nil then
                CastQ(Qtarget)
                ExtendedQ(Qtarget, false)
            end

        end
        CheckDashes()

        if Config.Flee then
          Flee()
        end 

        if Config.Farm then
          Farm()
        end
        KillSteal()
    end
end

function Flee()
  local CloseEnemy, CloseDistance = GetClosestEnemy(myHero)
  if CloseEnemy ~= nil and CloseDistance < 800 and myHero.mana < 60 and ValidTarget(CloseEnemy, 900) then
    CastQ(CloseEnemy)
  end
  if WReady then
    CastSpell(_W, myHero)
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

        if Config.ComboSub.useW and not IsMyManaLow() then
            CastW(Target)
        end

        if Config.ComboSub.useR then
            CheckRHealth(myHero, (Config.ComboSub.MinRHealth/100)*myHero.maxHealth)
        end

        if Config.ComboSub.useR then
            CheckRAllies(Config.ComboSub.RKnockup)
        end
    end 
end

function Harass(Target)
    if Target ~= nil and not IsMyManaLow() then
        CastE(Target)
        CastQ(Target)
    end
end

function Support()
    local Allies = GetAllyHeroes()
    for idx, ally in ipairs(Allies) do

    end
end


function Farm()
    if Config.FarmSub.AoEQ and Config.FarmSub.useQ and Config.FarmSub.useE then
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
                AoEQ(Target, false)
            end
            if Config.Extras.ExtendQ then
                ExtendedQ(Target)
            end
            if not Config.Extras.AoEQ and not Config.Extras.ExtendQ then
                RegularQ(Target)
            end
        end
    end 
end



function CastW(Target)
    if Target ~= nil and not Target.dead and WReady and GetDistance(Target) < Config.Extras.WDistance and not IsMyManaLow() then
        CastSpell(_W, Target)
    elseif WReady and GetDistance(Target) < 1500  and GetDistance(Target) > Config.Extras.WChaseDistance and not IsMyManaLow() then
      local PredictedPos, _ = CombinedPos(Target, 0.500, math.huge, myHero, false)
      if GetDistance(Target) - GetDistance(PredictedPos) > 250 then
        CastSpell(_W, myHero)
      end
    elseif WReady and GetDistance(Target) < 1100 and not IsMyManaLow() then
      local PredictedPos, _ = CombinedPos(Target, 0.500, math.huge, myHero, false)
      if GetDistance(Target) > GetDistance(PredictedPos) + 250  and GetDistance(PredictedPos) > Config.Extras.WChaseDistance then
        if GetDistance(Target) < SpellW.Range then
          CastSpell(_W, Target)
        else
          CastSpell(_W, myHero)
        end
      end
    end
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
        local CastPosition, Hitchance, Position = VP:GetLineCastPosition(Target, Delay, Width, Range, Speed, myHero, Collision)
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
    if Target == nil or Target.dead then return end
    if Collision == nil then Collision = false end
    if not Config.Extras.Prodiction then
        local PredictedPos, HitChance = VP:GetPredictedPos(Target, Delay, Speed, myHero, Collision)
        return PredictedPos, HitChance
    else
        local Pos, Info = Prodiction.GetPrediction(Target, Delay)
        if Pos ~= nil and Info ~= nil and Info.hitchance ~= nil then
            return Pos, Info.hitchance
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
--Compute highest possible EQ location 
--Compare vs single E
--See if worth extend
--Profit??

function AoEQ(Target)
  if Config.Extras.ExtendedQ == false then return end
  if Target == nil or Target.dead then return end
  if Pix == nil then return end
  
  local function GenerateExtendedTable(Target)
    local ReturnTable = {}
    local ReturnTable2 = {}
    local EnemyChampions = GetEnemyHeroes()
    for idx, object in ipairs(EnemyChampions) do
      if object.valid and not object.dead and ValidTarget(object) and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
        table.insert(ReturnTable2, object)
      end
    end

    local AllyChampions = GetAllyHeroes()
    for idx, object in ipairs(AllyChampions) do
      if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
      end
    end

    if Config.Extras.PixMinion then 
      EnemyMinions:update()
      for idx, object in ipairs(EnemyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
          table.insert(ReturnTable, object)
        end
      end

      AllyMinions:update()
      for idx, object in ipairs(AllyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
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
      if ValidTarget(enemy) and not enemy.dead then
        local CurrentEnemyPos = CombinedPos(enemy, 0.500, math.huge, myHero, false)
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

  --Function to calculate how many hit
  local function GenerateTwoVectors(Target, ExtendObject)
    --Given Pos1-->4 (corresponding to extended vectors + hero vector, compute enemies hit)
    local EndPos1, EndPos2 = nil, nil
    if Target ~= nil and ExtendedObject ~= nil then
      local ExtendObject = PredictPixPosition(ExtendObject)
      local Position, Hitchance = CombinedPos(Target, 0.500, math.huge, myHero, false)
      if GetDistance(Target) < SpellQ2.Range + 100 then
        EndPos1 = Vector(myHero.visionPos) + Vector(Vector(Position) - Vector(myHero.visionPos)):normalized()*SpellQ.Range
        EndPos2 = Vector(ExtendObject) + Vector(Vector(Position) - Vector(ExtendObject)):normalized()*SpellQ.Range
      end
    end
    if Config.Extras.Debug then
      print('Computing hits' .. tostring(ComputeHits(myHero.visionPos, EndPos1, ExtendObject, EndPos2)))
    end
    return ComputeHits(myHero.visionPos, EndPos1, ExtendObject, EndPos2)
  end

  if QReady and Pix ~= nil and GetDistance(Target) < SpellQ1.Range + 25 and CountEnemyHero(Target, SpellQ1.Range+25) == 0 then
    local CastPos1, Hit1, Pos1 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ2.Range, SpellQ.Speed, Pix, false)
    local CastPos2, Hit2, Pos2 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, myHero, false)
    if CastPos2 ~= nil and Hit2 ~= nil and Hit2 >= Config.Extras.Hitchance and GetDistance(CastPos2) < SpellQ1.Range then
      CastSpell(_Q, CastPos2.x, CastPos2.z)
    elseif CastPos1 ~= nil and Hit1 ~= nil and Hit1 >= Config.Extras.Hitchance and GetDistance(CastPos1, Pix) < SpellQ1.Range then
      CastSpell(_Q, CastPos1.x, CastPos1.z)
    end
  elseif QReady and EReady and GetDistance(Target) < SpellQ2.Range + 25 and CountEnemyHero(Target, SpellQ1.Range+25) == 0 then
    ExtendedQ(Target)
  elseif EReady and QReady and myHero.mana > myHero:GetSpellData(_Q).mana + myHero:GetSpellData(_E).mana and GetDistance(Target) < SpellQ2.Range + 25  and CountEnemyHero(Target, SpellQ1.Range+25) > 0 then
    local BestSwitchLocation = nil
    --Compute Single hit if enemy is within range
    local SingleCaseHit = 0
    if GetDistance(Target) < SpellQ.Range + 100 and Pix ~= nil then
      CastPos3, Hit3, Pos3 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ.Range, SpellQ.Speed, myHero, false)
      local EndPos3 = Vector(myHero.visionPos) + Vector(Vector(CastPos3) - Vector(myHero.visionPos)):normalized()*SpellQ.Range
      local EndPos4 = Vector(Pix) + Vector(Vector(CastPos3) - Vector(Pix)):normalized()*SpellQ.Range
      SingleCastHit = ComputeHits(myHero.visionPos, EndPos3, Pix, EndPos4)
    end
    --Each position has two vectors -> from extended position to enemy or from myHero to enemy 
    local BestHits = 0
    local ExtendedTable, ExtendedEnemyTable = GenerateExtendedTable(Target)
    if #ExtendedEnemyTable >= 1 then
      for idx, val in ipairs(ExtendedEnemyTable) do
        local CurrentHits = GenerateTwoVectors(Target, val)
        if CurrentHits >= SingleCaseHit + Config.Extras.MinPix and CurrentHits > BestHits then
          BestHits = CurrentHits
          BestSwitchLocation = val
          if Config.Extras.Debug then
            print('Extending with Enemy Table')
          end
        end
      end
    elseif #ExtendedTable >= 1 then
      for idx, val in ipairs(ExtendedTable) do
        local CurrentHits = GenerateTwoVectors(Target, val)
        if CurrentHits >= SingleCaseHit + Config.Extras.MinPix and CurrentHits > BestHits then
          BestHits = CurrentHits
          BestSwitchLocation = val
          if Config.Extras.Debug then
            print('Extending with EXTENDED TABLE')
          end
        end
      end   
    end

    if BestSwitchLocation ~= nil then
      if GetDistance(BestSwitchLocation) < SpellE.Range then
        CastSpell(_E, BestSwitchLocation)
        Packet('S_CAST', {spellId = _E, toX = BestSwitchLocation.x, toY = BestSwitchLocation.z}):send()
        if Config.Extras.Debug then
          print('BestSwitchLocation found, casting E to it')
        end
      end
    else
      if CastPos3 ~= nil and Hit3 ~= nil then
        CastSpell(_Q, CastPos3.x, CastPos3.z)
        DelayAction(function() CastE(Target) end, 0.20)
        if Config.Extras.Debug then
          print('Single Cast still best option')
        end
      end
    end
  elseif Pix ~= nil and not EReady and QReady and GetDistance(Target) < SpellQ2.Range then
    local CastPos1, Hit1, Pos1 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ2.Range, SpellQ.Speed, Pix, false)
    local CastPos2, Hit2, Pos2 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, myHero, false)
    if CastPos2 ~= nil and Hit2 ~= nil and Hit2 >= Config.Extras.Hitchance and GetDistance(CastPos2) < SpellQ1.Range then
      CastSpell(_Q, CastPos2.x, CastPos2.z)
    elseif CastPos1 ~= nil and Hit1 ~= nil and Hit1 >= Config.Extras.Hitchance and GetDistance(CastPos1) < SpellQ2.Range and GetDistance(CastPos1, Pix) < SpellQ1.Range then
      CastSpell(_Q, CastPos1.x, CastPos1.z)
    end
  end
end

function ExtendedQ(Target)
  if not Config.Extras.ExtendQ then return end
  local function GenerateExtendedTable(Target)
    local ReturnTable = {}
    local ReturnTable2 = {}
    local EnemyChampions = GetEnemyHeroes()
    for idx, object in ipairs(EnemyChampions) do
      if object.valid and not object.dead and ValidTarget(object) and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
        table.insert(ReturnTable2, object)
      end
    end

    local AllyChampions = GetAllyHeroes()
    for idx, object in ipairs(AllyChampions) do
      if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
        table.insert(ReturnTable, object)
      end
    end

    if Config.Extras.PixMinion then 
      EnemyMinions:update()
      for idx, object in ipairs(EnemyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
          table.insert(ReturnTable, object)
        end
      end

      AllyMinions:update()
      for idx, object in ipairs(AllyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
          table.insert(ReturnTable, object)
        end
      end
    end
    return ReturnTable, ReturnTable2
  end

  local function WillHit(Target, ExtendObject)
    if ExtendObject ~= nil and Target ~= nil and GetDistance(Target) < SpellQ2.Range + 50 and GetDistance(ExtendObject) < SpellE.Range and GetDistance(ExtendObject, Target.visionPos) < SpellQ1.Range then
      local Position, Hitchance = CombinedPos(Target, 0.500, math.huge, myHero, false)
      local ExtendObject = PredictPixPosition(ExtendObject)
      if GetDistance(Position, ExtendObject) < SpellQ1.Range then
        return true
      end
    end
    return false
  end


  if QReady and GetDistance(Target) < SpellQ1.Range then
    RegularQ(Target)
  elseif EReady and QReady and GetDistance(Target) < SpellQ2.Range + 50 and myHero.mana > myHero:GetSpellData(_Q).mana + myHero:GetSpellData(_E).mana  then
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

  elseif Pix ~= nil and not EReady and QReady and GetDistance(Target) < SpellQ2.Range + 50 then
    local CastPos1, Hit1, Pos1 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ2.Range, SpellQ.Speed, Pix, false)
    local CastPos2, Hit2, Pos2 = CombinedPredict(Target, SpellQ.Delay, SpellQ.Width, SpellQ1.Range, SpellQ.Speed, myHero, false)
    if CastPos2 ~= nil and Hit2 ~= nil and Hit2 >= Config.Extras.Hitchance and GetDistance(CastPos2) < SpellQ1.Range then
      CastSpell(_Q, CastPos2.x, CastPos2.z)
    elseif CastPos1 ~= nil and Hit1 ~= nil and Hit1 >= Config.Extras.Hitchance and GetDistance(CastPos1) < SpellQ2.Range and GetDistance(CastPos1, Pix) < SpellQ1.Range then
      CastSpell(_Q, CastPos1.x, CastPos1.z)
    end
  end
end



function AoEFarm()
  if Config.Extras.ExtendedQ == false then return end
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
    if Config.Extras.PixMinion then 
      EnemyMinions:update()
      for idx, object in ipairs(EnemyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range and getDmg("E", object, myHero) + 20 < object.health then
          table.insert(ReturnTable, object)
        end
      end

      AllyMinions:update()
      for idx, object in ipairs(AllyMinions.objects) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
          table.insert(ReturnTable, object)
        end
      end

      local AllyChampions = GetAllyHeroes()
      for idx, object in ipairs(AllyChampions) do
        if object.valid and not object.dead and GetDistance(object, Target) < SpellQ.Range+100 and object.networkID ~= Target.networkID and GetDistance(object) < SpellE.Range then
          table.insert(ReturnTable, object)
        end
      end

    end
    -- if Config.Extras.Debug then
    --   print('Size of return table in AoE Q is '.. tostring(#ReturnTable) .. "Enemy Table " .. tostring(#ReturnTable2))
    -- end
    return ReturnTable
  end



  local function GenerateExtendedTableTwo()
    local ReturnTable = {}
    if Config.Extras.PixMinion then 
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
      if ValidTarget(enemy) and not enemy.dead then
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
    if Target ~= nil and ExtendedObject ~= nil then
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
    return ComputeHits(myHero.visionPos, EndPos1, ExtendObject, EndPos2)
  end



  if EReady and QReady and #EnemyMinions.objects > 1 and Pix ~= nil then
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

    if SecondPosition ~= nil and SecondHit > 0 and SecondHit > NumHit + Config.Extras.MinPixFarm then
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
          if Current_Hit >= NumHit + Config.Extras.MinPixFarm and Current_Hit > BestSwitchHit then
            BestSwitchLocation = val
            BestSwitchHit = Current_Hit
          end
        end
      end

      if BestSwitchLocation ~= nil then
        CastSpell(_E, BestSwitchLocation)
        if Config.Extras.Debug then
          print("Casting BestSwitchLocation E")
        end
      else
        local NumHit1, FirstPosition1 = BestHitVector(myHero)
        local NumHit2, FirstPosition2 = BestHitVector(Pix)
        if NumHit1 == NumHit2 and FirstPosition1 ~= FirstPosition2 then
          CastSpell(_Q, FirstPosition1.x, FirstPosition1.z)
        elseif NumHit1 > NumHit2 and FirstPosition1 ~= FirstPosition2 then
          CastSpell(_Q, FirstPosition1.x, FirstPosition1.z)
        elseif NumHit1 < NumHit2 and FirstPosition1 ~= FirstPosition2 then
          CastSpell(_Q, FirstPosition2.x, FirstPosition2.z)
        end  
        if Config.Extras.Debug then
          print("Casting BestSwitchLocation Q")
        end
      end
    end
  elseif QReady and not EReady and #EnemyMinions.objects > 1 and Pix ~= nil then
    local NumHit1, FirstPosition1 = BestHitVector(myHero)
    local NumHit2, FirstPosition2 = BestHitVector(Pix)
    if NumHit1 == NumHit2 and FirstPosition1 ~= FirstPosition2 then
      CastSpell(_Q, FirstPosition1.x, FirstPosition1.z)
    elseif NumHit1 > NumHit2 and FirstPosition1 ~= FirstPosition2 then
      CastSpell(_Q, FirstPosition1.x, FirstPosition1.z)
    elseif NumHit1 < NumHit2 and FirstPosition1 ~= FirstPosition2 then
      CastSpell(_Q, FirstPosition2.x, FirstPosition2.z)
    end
  elseif QReady and #EnemyMinions.objects < 2 then
    if GetDistance(EnemyMinions.objects[1]) < SpellQ1.Range then
      CastSpell(_Q, EnemyMinions.objects[1].x, EnemyMinions.objects[1].z )
    end
  end
end


function RegularQ(Target)
    if not QReady then return end
    if Target ~= nil and ValidTarget(Target) and not Target.dead and GetDistance(Target) < 1100 and ValidTarget(Target) and not Target.dead then
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
                    if HitChance >= 1 and GetDistance(Pos, Target) < 150 + VP:GetHitBox(enemy) and RReady then
                        count = count + 1
                    end 
                end
            else
                local Pos = tp4:GetPrediction(enemy)
                if GetDistance(Pos, Target) < 200 and RReady then
                    count = count + 1
                end
            end
        end
        return count 
    end
end

function CheckRAllies(Hits)
    local Allies = GetAllyHeroes()
    for idx, champion in ipairs(Allies) do
        local current_hits = GetRHits(champion)
        if current_hits ~= nil then
          if current_hits >= Hits then
              CastR(champion)
          end
        end
    end

end

function CheckRHealth(Target, Health)
    if Target ~= nil and not Target.dead then
        if Target.health < Health and RReady and GetDistance(Target) < SpellR.Range then
            CastR(Target)
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
                if VIP_USER then
                    local throwaway, HitChance, PredictedPos = CombinedPredict(Target, delay, SpellQ.Width, SpellQ.Range, SpellQ.Speed, myHero, false)
                    local pointSegment, pointLine, isOnSegment = VectorPointProjectionOnLineSegment(Vector(startpos), Vector(endpos), Vector(PredictedPos))
                    local pointSegment3D = {x=pointSegment.x, y=enemy.y, z=pointSegment.y}
                    if isOnSegment and pointSegment3D ~= nil and GetDistance(pointSegment3D, PredictedPos) < VP:GetHitBox(enemy) + SpellQ.Width and HitChance >= 1 then
                        count = count + 1
                    end
                else
                    local PredictedPos, a, b = tp1:GetPrediction(enemy)
                    local pointSegment, pointLine, isOnSegment = VectorPointProjectionOnLineSegment(Vector(startpos), Vector(endpos), Vector(PredictedPos))
                    local pointSegment3D = {x=pointSegment.x, y=enemy.y, z=pointSegment.y}
                    if isOnSegment and pointSegment3D ~= nil and GetDistance(pointSegment3D, PredictedPos) < 50 + SpellQ.Width then
                        count = count + 1
                    end
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
    if VIP_USER then
        local TargetWaypoints = VP:GetCurrentWayPoints(Target)
        if #TargetWaypoints > 1 then
            local PredictedPos = CombinedPos(Target, 0.250, math.huge, myHero, false)
            local UnitVector = Vector(Vector(PredictedPos) - Vector(Target)):normalized()
            local PixPosition = Vector(PredictedPos) - Vector(UnitVector)*(VP:GetHitBox(Target) + 100)
            -- if Config.Extras.Debug then
            --     print('Pix Position returning ' .. tostring(PixPosition.z))
            -- end
            return PixPosition
        else
            return Target
        end
    else
        local Destination1, a, b = tp4:GetPrediction(Target)
        if Destination1 ~= nil then
            local UnitVector = Vector(Vector(Destination1) - Vector(Target)):normalized()
            --local UnitVector = Vector(Vector(Destination13D) - Vector(Target)):normalized()
            local PixPosition = Vector(Target) - Vector(UnitVector)*(150)
            return PixPosition
        else
            return Target
        end
    end
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


function OnProcessSpell(unit, spell)
    if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
    if #ToInterrupt > 0 and Config.Extras.WSpells and WReady then
        for _, ability in pairs(ToInterrupt) do
            if spell.name == ability and unit.team ~= myHero.team and GetDistance(unit) < SpellW.Range then
                CastSpell(_W, unit.x, unit.z)
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
end

function OnSendPacket(packet)
    if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
    if packet.header == 0x71 then
        packet.pos = 1
        packetNWID = packet:DecodeF();
        if packetNWID ~= myHero.networkID then
           PixnetworkID = packetNWID
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
end


-- function OnCreateObj(object)
--     if object and object.name:lower():find("faerie") then
--         Pix = object
--      if Config.Extras.Debug then
--          print('Pix Created')
--      end
--     end

-- end

-- function OnDeleteObj(object)
--     if object and object.name:lower():find("faerie")  then
--         Pix = nil
--      if Config.Extras.Debug then
--          print('Pix Destroyed')
--      end
--     end
-- end

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



function ProcessPix()
    if PixnetworkID ~= nil then
        -- if GetTickCount() - last_pix_time > 1 then
            Pix = objManager:GetObjectByNetworkId(PixnetworkID)
            -- if Config.Extras.Debug then
            --   print(Pix.name)
            -- end
        --     last_pix_time = GetTickCount()
        -- end
    end
end

function OnDraw()
    if not initDone or not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
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

    if Config.Draw.DrawQ then
        DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellQ.Range, 1,  ARGB(255, 0, 255, 255))
    end

    if Config.Draw.DrawQ2 then
        DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellQ2.Range, 1,  ARGB(255, 0, 255, 255))
    end

    if Config.Draw.DrawW then
        DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellW.Range, 1,  ARGB(255, 0, 255, 255))
    end

    if Config.Draw.DrawR then
        DrawCircle3D(myHero.x, myHero.y, myHero.z, SpellR.Range, 1,  ARGB(255, 0, 255, 255))
    end

    if Config.Draw.DrawTarget then
        if target ~= nil then
            DrawCircle3D(target.x, target.y, target.z, 100, 1, ARGB(255, 255, 0, 0))
        elseif Qtarget ~= nil then
            DrawCircle3D(Qtarget.x, Qtarget.y, Qtarget.z, 100, 1, ARGB(255, 255, 0, 0))
        end
    end

    if Config.Draw.DrawPix then
        if Pix ~= nil then
            DrawCircle3D(Pix.x, Pix.y, Pix.z, 100, 1, ARGB(255, 255, 255, 0))
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