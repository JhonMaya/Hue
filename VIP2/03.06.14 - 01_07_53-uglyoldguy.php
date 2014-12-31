<?php exit() ?>--by uglyoldguy 68.48.159.9
local version = "0.06"
if myHero.charName ~= "Rumble" then return end
function r(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end
_ENV[r({115,99,114,105,112,116,118,101,114})] = 0.06 -- version
require 'VPrediction'
local ProdFile = LIB_PATH .. "Prodiction.lua"
local fh = io.open(ProdFile, 'r')
if fh ~= nil then
  local line = fh:read()
  local Version = string.match(line, "%d+.%d+")

  if tonumber(Version) > 0.8 then
    ProdOneLoaded = true
  end
  if ProdOneLoaded then
    require 'Prodiction'
    print("<font color=\"#FF0000\">Prodiction 1.0 Loaded for DienoRumble, 1.0 option is usable</font>")
  else
    print("<font color=\"#FF0000\">Prodiction 1.0 not detected for DienoRumble, 1.0 is not usable (will cause errors if checked)</font>")
  end
else
  print("<font color=\"#FF0000\">No Prodiction Lua detected, using only VPRED</font>")
end
--Honda7
math.randomseed(os.time()+GetInGameTimer()+GetTickCount())
local AUTOUPDATE = true
local UPDATE_NAME = "Rumble"
local UPDATE_HOST = "raw.github.com"
local VERSION_PATH = "/Dienofail/BoL/master/versions/Rumble.version" .."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH..GetCurrentEnv().FILE_NAME
local UPDATE_URL = "http://www.dienofail.com/Rumble.lua" .. "?rand=" .. math.random(1,100000)
function AutoupdaterMsg(msg) print("<font color=\"#FF0000\"><b>DienoRumble:</b></font> <font color=\"#FF0000\">"..msg..".</font>") end
if AUTOUPDATE then
    local ServerData = GetWebResult(UPDATE_HOST, VERSION_PATH)
    if ServerData then
        local ServerVersion = string.match(ServerData, "%d+.%d+")
        if ServerVersion then
            ServerVersion = tonumber(ServerVersion)
            if tonumber(version) < ServerVersion then
                AutoupdaterMsg("New version available"..ServerVersion)
                AutoupdaterMsg("Updating, please don't press F9")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version after auth.") end) end, 3)
            else
                AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
            end
        end
    else
        AutoupdaterMsg("Error downloading version info")
    end
end

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
_ENV[r({115,99,114,105,112,116,110,97,109,101})] = 'rumble' -- script name
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
      DelayAction(checkOrbwalker, 7)
      DelayAction(Menu, 7.5)
      DelayAction(Init, 7.5)
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


local VP = VPrediction()
local SpellQ = {Range = 600, Angle = 30, Delay = 0.250, Speed = 5000, CD = 6000}
local SpellW = {CD = 6000}
local SpellE = {Range = 950, Delay = 0.250, Speed = 2000, CD = 10000, Width = 70}
local SpellR = {Range = 1700, Delay = 0.250, Speed = 1600, WallLength = 900, Width = 90}
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
    ts2 = TargetSelector(TARGET_LESS_CAST_PRIORITY, 1500, DAMAGE_MAGICAL)
    ts.name = "Rumble Main"
    ts2.name = "Rumble Ult"
    Config:addTS(ts)
    Config:addTS(ts2)
    EnemyMinions = minionManager(MINION_ENEMY, 800, myHero, MINION_SORT_MAXHEALTH_DEC)
    JungleMinions = minionManager(MINION_JUNGLE, 800, myHero, MINION_SORT_MAXHEALTH_DEC)
    print('Dienofail Rumble ' .. tostring(version) .. ' loaded!')
    initDone = true
end


function Menu()
    Config = scriptConfig("Rumble", "Rumble")
    Config:addParam("Combo", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
    Config:addParam("Harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('C'))
    Config:addParam("Farm", "Farm", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('V'))
    Config:addParam("SmartUlt", "Smart Ult", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('Z'))
    Config:addParam("Flee", "Flee", SCRIPT_PARAM_ONKEYDOWN, false, string.byte('T'))
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
    Config.Extras:addParam("Manageheat", "Always stay in danger zone", SCRIPT_PARAM_ONOFF, true)
    Config.Extras:addParam("Prodiction", "Use Prodiction 1.0 instead of VPred", SCRIPT_PARAM_ONOFF, false)
    if IsSowLoaded then
      Config:addSubMenu("Orbwalker", "SOWiorb")
      SOWi:LoadToMenu(Config.SOWiorb)
      Config.SOWiorb.Mode0 = false
    end
    --Permashow
    Config:permaShow("Combo")
    Config:permaShow("Harass")
    Config:permaShow("Farm")
end


function GetCustomTarget()
    ts:update()
    if _G.MMA_Target and _G.MMA_Target.type == myHero.type then return _G.MMA_Target end
    if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair and _G.AutoCarry.Attack_Crosshair.target and _G.AutoCarry.Attack_Crosshair.target.type == myHero.type then return _G.AutoCarry.Attack_Crosshair.target end
    return ts.target
end

function OnTick()
    if initDone and _ENV[r({107,101,107,118,97,108,56,51,52})] then
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
        KillSteal()
        AutoManageHeat()
        if Config.Flee then
          Flee()
        end
    end
end

function Flee()
  local CloseEnemy, CloseDistance = GetClosestEnemy(myHero)
  if CloseEnemy ~= nil and CloseDistance < 800 and myHero.mana < 60 and ValidTarget(CloseEnemy, 900) then
    CastE(CloseEnemy)
  end
  if WReady then
    CastSpell(_W)
  end
end


function AutoManageHeat()
  if isRecalling then
    return
  end
  if Config.Extras.Manageheat and CountEnemyHeroInRange(1000, myHero) < 2 then
      if WReady and Config.Heat.useW and myHero.mana < 35 and GetTickCount() - LastSpellCast > 750 then
          CastSpell(_W)
      elseif QReady and Config.Heat.useQ and  myHero.mana < 35 and GetTickCount() - LastSpellCast > 750 and not CheckQCreeps() then
          CastQRegular()
      end
  elseif Config.Extras.Manageheat then
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

  if Config.ComboSub.useE and EReady and isSecondE and GetDistance(Target) < 1000 then
    if Config.Extras.SpaceE and GetTickCount() - LastSpellCast > 250 and GetDistance(Target) >= 300 then
      CastE(Target)
    elseif (Config.Extras.SpaceE and GetTickCount() - LastETick > 1000*Config.Extras.SpaceETime and GetDistance(Target) < 300) then
      CastE(Target)
    elseif not Config.Extras.SpaceE and GetTickCount() - LastSpellCast > 250 then
      CastE(Target)
    end
  end

  if Config.ComboSub.useE and not isSecondE and EReady and ShouldCastQE and GetDistance(Target) < 1000 and GetTickCount() - LastSpellCast > 250  then 
    CastE(Target)
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

      if getDmg("Q", enemy, myHero, 1) > enemy.health and GetDistance(PredictedPos) < SpellQ.Range then
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
  end
end

function CastQCombo(Target)
  if Target ~= nil and ValidTarget(Target) and not Target.dead then
    local FaceVector = Vector(Vector(myHero.visionPos) - Vector(myHero)):normalized()
    local EnemyVector = Vector(Vector(Target.visionPos) - Vector(myHero.visionPos)):normalized()
    if FaceVector:angle(EnemyVector) < 30 * 0.0174532925 or FaceVector:angle(EnemyVector) > 2*math.pi - 30*0.0174532925 and GetDistance(Target) < 700 then
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
    if FaceVector:angle(MinionVector) < 30 * 0.0174532925 or FaceVector:angle(MinionVector) > 2*math.pi - 30*0.0174532925 and GetDistance(minion) < 700 then
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
        if info ~= nil and info.hitchance ~= nil and CastPosition ~= nil then 
          HitChance = info.hitchance
        end
      end
      if CastPosition ~= nil and HitChance ~= nil and HitChance > 1 and GetDistance(CastPosition) < SpellE.Range then 
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
        local Position1 = Vector(midpoint) - FinalInitialUnitVector*360
        if not CheckWall(Position1, Position2) then
          return Position1, Position2
        end
      elseif GetDistance(current_waypoints[1], current_waypoints[#current_waypoints]) < SpellR.WallLength and travel_time >= 0.4 then
        local Position2 = Vector(midpoint) + FinalInitialUnitVector*400
        local Position1 = Vector(midpoint) - FinalInitialUnitVector*390
        if not CheckWall(Position1, Position2) then
          return Position1, Position2
        end
      elseif GetDistance(current_waypoints[1], current_waypoints[#current_waypoints]) < SpellR.WallLength and travel_time >= 0 then
        local Position2 = Vector(midpoint) + FinalInitialUnitVector*350
        local Position1 = Vector(midpoint) - FinalInitialUnitVector*420
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
    if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
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

function OnLoseBuff(unit, buff)
    if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
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
    if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
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
    if initDone and _ENV[r({107,101,107,118,97,108,56,51,52})] then
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