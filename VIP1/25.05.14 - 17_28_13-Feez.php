<?php exit() ?>--by Feez 24.14.208.12
if myHero.charName ~= "Annie" then return end

require 'VPrediction'
require 'SOW'
------------------------------------------------------------------------------------
------------------------------------------------------------------------------------
--[[

Auth Script by Xetrok
2.0 

]]

function r(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
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

_ENV[r({100,101,118,110,97,109,101})] = r({102,101,101,122}) -- devname
_ENV[r({115,99,114,105,112,116,110,97,109,101})] = 'annie the unbearable' -- script name
_ENV[r({115,99,114,105,112,116,118,101,114})] = 4.6 -- version
_ENV[r({104,49})] = r({98,111,108,97,117,116,104,46,99,111,109}) -- main host
_ENV[r({104,50})] = r({98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109}) -- backup host
_ENV[r({104,51})] = r({122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109}) -- us host
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
local kekval178 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,111,108,97,117,116,104,46,99,111,109})
local kekval179 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,98,97,99,107,117,112,46,98,111,108,97,117,116,104,46,99,111,109})
local kekval180 = r({104,116,116,112,58,47,47,119,119,119,46,100,111,119,110,102,111,114,101,118,101,114,121,111,110,101,111,114,106,117,115,116,109,101,46,99,111,109,47,122,101,117,115,46,98,111,108,97,117,116,104,46,99,111,109})
local kekval181 = LIB_PATH..r({99,104,101,99,107,49,46,116,120,116})
local kekval182 = LIB_PATH..r({99,104,101,99,107,50,46,116,120,116})
local kekval183 = LIB_PATH..r({99,104,101,99,107,51,46,116,120,116})

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
end

function Kek11()

  if (g1 == false) then 
    _ENV[r({75,101,107,49,50})](1) -- Main Server
    return
  end
  if (g3 == false) then 
    _ENV[r({75,101,107,49,50})](3) -- US Server
    return
  end
  if (g2 == false) then 
    _ENV[r({75,101,107,49,50})](2) -- Backup server
    return
  end

  if (g1 == true) and (g2 == true) and (g3 == true) then
    PrintChat(r({60,102,111,110,116,32,99,111,108,111,114,61,39,35,68,66,48,48,48,52,39,62,65,110,110,105,101,58,32,78,111,32,115,101,114,118,101,114,115,32,97,118,97,105,108,97,98,108,101,60,47,102,111,110,116,62}))
  end
end

function Kek4(authCheck)
  areturn = _ENV[r({82,67,52})](rc(rd(authCheck)),false)
  dePack = JSON:decode(areturn)
  if (dePack[r({115,116,97,116,117,115})]) then
    if (dePack[r({115,116,97,116,117,115})] == 1) or (dePack[r({115,116,97,116,117,115})] == 7) then
      PrintChat(r({60,102,111,110,116,32,99,111,108,111,114,61,39,35,68,66,48,48,48,52,39,62,65,110,110,105,101,58,32,65,117,116,104,101,110,116,105,99,97,116,101,100,60,47,102,111,110,116,62}))
      _ENV[r({107,101,107,118,97,108,56,51,52})] = true
      	LoadConfig()
    else
      reason = dePack[r({114,101,97,115,111,110})]
      PrintChat(r({60,102,111,110,116,32,99,111,108,111,114,61,39,35,68,66,48,48,48,52,39,62,65,110,110,105,101,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100,32,45,32,60,47,102,111,110,116,62})..reason)
    end
  end

end

function Kek6(hex)
        local str, n = hex:gsub("(%x%x)[ ]?", function (word)
                return string.char(tonumber(word, 16))
        end)
        return str
end

--[[

function OnTick()
  if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end -- this is easily overridden just fyi, make sure you have important init stuff in kek4
  --stuff here
end

function OnDraw()
  if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end -- this is easily overridden just fyi, make sure you have important init stuff in kek4
  --stuff here
end

]]




------------------------------------------------------------------------------------
------------------------------------------------------------------------------------

--[[
To Do (no order)
--------
-Don't waste mana in base pad in ARAM
]]
--[[
Bugs
---------
]]




local version = "4.6"
local TESTVERSION = false
local AUTOUPDATE = true
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/Feeez/BoL-Paid/master/Annie the UnBEARable.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."Annie the UnBEARable.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

DelayAction(function()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	function AutoupdaterMsg(msg) print("<font color='#DB0004'>Annie: "..msg.."</font>") end
	if AUTOUPDATE then
		local ServerData = GetWebResult(UPDATE_HOST, "/Feeez/BoL-Paid/master/annie.version")
		if ServerData then
			ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
			if ServerVersion then
				if tonumber(version) < ServerVersion then
					AutoupdaterMsg("New version available: "..ServerVersion)
					AutoupdaterMsg("Updating Annie. Do not F9 until done.")
					DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () AutoupdaterMsg("Updated version "..version.." to version "..ServerVersion..". Press F9 twice to finish update.") end) end, 3)
				else
					DelayAction(function() print("<font color='#DB0004'>Annie the Un</font><font color='#543500'>BEAR</font><font color='#DB0004'>able</font>") end, 1)
					DelayAction(function() print("<font color='#FFFFFF'>User: </font><font color='#F88017'>"..GetUser().."</font>") end, 1)
				end
			end
		else
			AutoupdaterMsg("Error downloading version info")
		end
	end
end, 10)


local comboRange = 625
local flash, ignite = nil, nil
local igniteready
local dfgslot = nil
local bftslot = nil
local fqcslot = nil
local hxtslot, hxtready, blgslot, blgready, odvslot, odvready = nil, nil, nil, nil, nil, nil
local qready, wready, eready, rready, dfgready, bftready, fqcready = false, false , false, false, false, false, false
local qmana, wmana, emana, rmana
local pyroStack = 0
local flashready = false
local finisher = false
local stunReady = false
local tibbersAlive = false
local ultAvailable = nil
local qdamage, wdamage, rdamage, comboDamage, AAdamage
local didCombo = nil
local doingCombo = false
local ctime = 0
local spawnTurret
local tryingAA, timeChanged = false
local aatime, aatime2 = 0,0
local tibLA, tibWind, tibAnimation = 0,0,0
local annieLA, annieWind, annieAnimation = 0,0,0
local annieReset
local tibbersObject, AAobject
local attackspeed, isRecalling
local ultTable, karthusUltTime, karthus, shieldTable
local checkVersion = true
local VPVersion
local QTime = 0
local QKillCasted = false
local SACloaded, MMAloaded
local ldCnfig = false
local stunSprite = nil
local lichbane
local farmTurnedOff = false
--local ts = {}


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
	PrintChat(r({60,102,111,110,116,32,99,111,108,111,114,61,39,35,68,66,48,48,48,52,39,62,65,110,110,105,101,58,32,86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115})) -- Validating Access
	_ENV[r({68,101,108,97,121,65,99,116,105,111,110})](_ENV[r({75,101,107,49,49})],4) -- run the auth after checking sites delayaction,4
end


function LoadConfig()
	AUConfig = scriptConfig("Annie the UnBEARable", "annieconfig")

	AUConfig:addSubMenu("Combo", "combosettings")
	AUConfig:addSubMenu("Harass", "harasssettings")
	AUConfig:addSubMenu("Orbwalker [SOW]", "orbwalker")
	AUConfig:addSubMenu("Stun", "stunsettings")
	AUConfig:addSubMenu("Auto Farm", "farmsettings")
	AUConfig:addSubMenu("Auto Shield", "autoshield")
	AUConfig:addSubMenu("Draw", "drawsettings")
	AUConfig:addSubMenu("Spell Casting", "castsettings")
	AUConfig:addSubMenu("Tibbers", "tibbers")
	AUConfig:addSubMenu("Passive", "passive")

	AUConfig.autoshield:addParam("info", "Turn off E stack for better results", SCRIPT_PARAM_INFO, "")
	AUConfig.autoshield:addParam("enabled", "Enabled", SCRIPT_PARAM_ONOFF, true)

	AUConfig.stunsettings:addSubMenu("Defensive Stun", "defensiveult")
	AUConfig.stunsettings:addParam("autoStunW", "Auto Stun enemies with W", SCRIPT_PARAM_ONOFF, true)
	AUConfig.stunsettings:addParam("autoStunR", "Auto Stun enemies with R (100% hit)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings:addParam("autoStunWvalue", "least # of enemies to auto stun", SCRIPT_PARAM_SLICE, 3, 1, 5, 0)
	AUConfig.stunsettings:addParam("autoStunCombo", "Combo after auto stun (if worked)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.stunsettings:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.stunsettings:addParam("autoStunTower", "Auto Stun enemies focused by tower", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings.defensiveult:addParam("stunUlt", "Defensive Stun", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings.defensiveult:addParam("comboUlt", "Full combo if stunned", SCRIPT_PARAM_ONOFF, false)
	AUConfig.stunsettings.defensiveult:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")

	AUConfig.castsettings:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("select1", "Cast Method (select one)", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("method1", "AoE (VPrediction too, best option)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.castsettings:addParam("method2", "No method (focus TS target)", SCRIPT_PARAM_ONOFF, false)
	AUConfig.castsettings:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.castsettings:addParam("spellexploit", "Use directional exploit", SCRIPT_PARAM_ONOFF, true)
	AUConfig.castsettings:addParam("antimiss", "Anti-miss W", SCRIPT_PARAM_ONOFF, true)

	AUConfig.farmsettings:addParam("qfarm", "Auto Farm with Q", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("Z"))
	AUConfig.farmsettings:addParam("qfarmstop1", "Stop Q farm when stun ready", SCRIPT_PARAM_ONKEYTOGGLE, true, GetKey("J"))
	AUConfig.farmsettings:addParam("reactivate", "Auto reactivate farm mode", SCRIPT_PARAM_ONOFF, true)
	AUConfig.farmsettings:permaShow("qfarm") 
	AUConfig.farmsettings:permaShow("qfarmstop1")

	AUConfig.harasssettings:addParam("harass", "Harass", SCRIPT_PARAM_ONKEYDOWN, false, GetKey("T"))
	AUConfig.harasssettings:addParam("movetomouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassQ", "Use Q", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassW", "Use W", SCRIPT_PARAM_ONOFF, true)
	AUConfig.harasssettings:addParam("harassStun", "Use Stun", SCRIPT_PARAM_ONOFF, false)
	AUConfig.harasssettings:addParam("screwAA", "Block AAs out of range [when spells ready]", SCRIPT_PARAM_ONOFF, true)

	AUConfig.drawsettings:addParam("lagfree", "Lag Free Draw", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("div", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.drawsettings:addParam("drawaarange", "Draw AA range", SCRIPT_PARAM_ONOFF, false)
	AUConfig.drawsettings:addParam("aaColor", "Color:", SCRIPT_PARAM_COLOR, {255,0,255,17})
	AUConfig.drawsettings:addParam("drawharassrange", "Draw combo range", SCRIPT_PARAM_ONOFF, false)
	AUConfig.drawsettings:addParam("harassColor", "Color:", SCRIPT_PARAM_COLOR, {255,134,104,222})
	AUConfig.drawsettings:addParam("drawflashrange", "Draw flash + combo range", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("flashColor", "Color:", SCRIPT_PARAM_COLOR, {255,222,18,222})
	AUConfig.drawsettings:addParam("circleTarget", "Draw circle under TS target", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("targetColor", "Color:", SCRIPT_PARAM_COLOR, {255, 255, 0, 0})
	AUConfig.drawsettings:addParam("div2", "_________________________________", SCRIPT_PARAM_INFO, "")
	AUConfig.drawsettings:addParam("drawkillable", "Draw killable text on target", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("drawtibbers", "Draw timer of tibbers on yourself", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("drawstun", "Draw stun sprite", SCRIPT_PARAM_ONOFF, true)
	AUConfig.drawsettings:addParam("fix", "Text draw fix", SCRIPT_PARAM_ONOFF, true)

	AUConfig.combosettings:addParam("comboActive", "Combo", SCRIPT_PARAM_ONKEYDOWN, false, 32)
	AUConfig.combosettings:addParam("movetomouse", "Move to mouse", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:addParam("useflash", "Use Flash when Killable", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:addParam("ignite", "Calculate ignite into combo", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:addParam("screwAA", "Block AAs out of range [when spells ready]", SCRIPT_PARAM_ONOFF, true)
	AUConfig.combosettings:permaShow("comboActive")

	AUConfig.tibbers:addParam("directTibbers", "Auto control & orbwalk tibbers", SCRIPT_PARAM_ONOFF, true)
	AUConfig.tibbers:addParam("followTibbers", "Tibbers follow toggle", SCRIPT_PARAM_ONKEYTOGGLE, false, GetKey("N"))
	AUConfig.tibbers:permaShow("followTibbers")

	AUConfig.passive:addParam("passiveStack", "Stack passive in spawn (w and e)", SCRIPT_PARAM_ONOFF, true)
	AUConfig.passive:addParam("passiveStack2", "Stack passive everywhere (with e)", SCRIPT_PARAM_ONOFF, false)

	AUConfig:addParam("autolevel", "Auto level ult", SCRIPT_PARAM_ONOFF, true)
	AUConfig:addParam("version", "Version:", SCRIPT_PARAM_INFO, version)

	--Selector.Instance() 

	--TS
	ts = TargetSelector(TARGET_LESS_CAST_PRIORITY,1000,DAMAGE_MAGIC)
	ts.name = "Annie"
	ts.targetSelected = true
	AUConfig.combosettings:addTS(ts)
	AUConfig.combosettings.comboActive = false

	VP = VPrediction()

	SOWi = SOW(VP)
	SOWi:LoadToMenu(AUConfig.orbwalker)
	SOWi:EnableAttacks()
	
	--DelayAction(function() print("<font color='#DB0004'>Annie the Un</font><font color='#543500'>BEAR</font><font color='#DB0004'>able</font>") end, 1)
	--DelayAction(function() print("<font color='#FFFFFF'>User: </font><font color='#F88017'>"..GetUser().."</font>") end, 1)
	
	if myHero:GetSpellData(SUMMONER_1).name:find("SummonerFlash") then flash = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_1).name:find("SummonerIgnite") then ignite = SUMMONER_1
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerFlash") then flash = SUMMONER_2 
	elseif myHero:GetSpellData(SUMMONER_2).name:find("SummonerIgnite") then ignite = SUMMONER_2
	end
	
	for i=1, objManager.maxObjects do
		local object = objManager:getObject(i)
		if object ~= nil and object.type == "obj_AI_Turret" then
			if (myHero.team == TEAM_BLUE and object.name == "Turret_OrderTurretShrine_A") or (myHero.team == TEAM_RED and object.name == "Turret_ChaosTurretShrine_A") then
				spawnTurret = object
			end
		end
	end
	
	
	ultTable = {
	['FiddleSticks'] = {spell = 'Crowstorm'},
	['Katarina'] = {spell = 'KatarinaR'},
	['Caitlyn'] = {spell = 'CaitlynAceintheHole'},
	['Lucian'] = {spell = 'LucianR'},
	['Ezreal'] = {spell = 'EzrealTrueshotBarrage'},
	['MissFortune'] = {spell = 'MissFortuneBulletTime'},
	['Karthus'] = {spell = 'FallenOne'},
	['Warwick'] = {spell = 'InfiniteDuress'},
	['Nunu'] = {spell = 'AbsoluteZero'},
	['Malzahar'] = {spell = 'AlZaharNetherGrasp'},
	['Syndra'] = {spell = 'SyndraR'}
	}
	
	shieldTable = {
	['KatarinaR'] = 1,
	['CaitlynAceintheHole'] = 1,
	['FallenOne'] = 1,
	['InfiniteDuress'] = 1,
	['AlZaharNetherGrasp'] = 1,
	['BlindingDart'] = 1,
	['YasuoQW'] = 1,
	['FioraDance'] = 1,
	['SyndraR'] = 1,
	['VeigarPrimordialBurst'] = 1,
	['VeigarBalefulStrike'] = 1,
	['BusterShot'] = 1,
	['frostarrow'] = 1
	}
	
	for i=1, heroManager.iCount do
		local enemy = heroManager:getHero(i)
		if ultTable[enemy.charName] ~= nil and enemy.team == TEAM_ENEMY then
			AUConfig.stunsettings.defensiveult:addParam(enemy.charName, enemy.charName, SCRIPT_PARAM_ONOFF, true)
		end
	end

	local function MINION_SORT_PRIORITY(a, b)
		if (a.maxHealth > b.maxHealth) then
			return true
		elseif (a.maxHealth == b.maxHealth) then
			return a.health < b.health
		elseif (a.maxHealth < b.maxHealth) then
			return false
		end
	end

	
	enemyMinions = minionManager(MINION_ENEMY, 625, player.visionPos, MINION_SORT_PRIORITY)

	stunSprite = GetWebSprite("https://raw.githubusercontent.com/Feeez/BoL-Paid/master/Sprites/pyromania.png")

	DelayAction(function() SOWi.MyRange = function() return 765.5 end end, 1)
	DelayAction(function() if SACloaded or MMAloaded then AUConfig.orbwalker.Enabled = false end end, 5)
	DelayAction(function() if _G.Activator and _G.Activator then _G.OffensiveItems = false; print("<font color='#DB0004'>Annie: Activator found</font>") end end, 3)
end


function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	radius = radius or 300
	quality = math.max(8,math.floor(180/math.deg((math.asin((chordlength/(2*radius)))))))
	quality = 2 * math.pi / quality
	radius = radius*.92
	local points = {}
	for theta = 0, 2 * math.pi + quality, quality do
		local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
		points[#points + 1] = D3DXVECTOR2(c.x, c.y)
	end
	DrawLines2(points, width or 1, color or 4294967295)
end

function DrawCircle2(x, y, z, radius, color)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	local vPos1 = Vector(x, y, z)
	local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
	local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
	local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
	if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y })  then
		DrawCircleNextLvl(x, y, z, radius, 1, color, 75)	
	end
end

function OnDraw()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end

	if AUConfig.drawsettings.lagfree then
		if AUConfig.drawsettings.circleTarget and ts.target ~= nil then
			DrawCircle2(ts.target.x, ts.target.y, ts.target.z, 70, ARGB(AUConfig.drawsettings.targetColor[1], AUConfig.drawsettings.targetColor[2], AUConfig.drawsettings.targetColor[3], AUConfig.drawsettings.targetColor[4]))
		end
		if AUConfig.drawsettings.drawflashrange then
			DrawCircle2(myHero.x, myHero.y, myHero.z, 1000, ARGB(AUConfig.drawsettings.flashColor[1], AUConfig.drawsettings.flashColor[2], AUConfig.drawsettings.flashColor[3], AUConfig.drawsettings.flashColor[4]))
		end
		if AUConfig.drawsettings.drawharassrange then
			DrawCircle2(myHero.x, myHero.y, myHero.z, 650, ARGB(AUConfig.drawsettings.harassColor[1], AUConfig.drawsettings.harassColor[2], AUConfig.drawsettings.harassColor[3], AUConfig.drawsettings.harassColor[4]))
		end
		if AUConfig.drawsettings.drawaarange then
			DrawCircle2(myHero.x, myHero.y, myHero.z, 765.5, ARGB(AUConfig.drawsettings.aaColor[1], AUConfig.drawsettings.aaColor[2], AUConfig.drawsettings.aaColor[3], AUConfig.drawsettings.aaColor[4]))
		end
	else
		if AUConfig.drawsettings.circleTarget and ts.target ~= nil then
			DrawCircle(ts.target.x, ts.target.y, ts.target.z, 70, ARGB(AUConfig.drawsettings.targetColor[1], AUConfig.drawsettings.targetColor[2], AUConfig.drawsettings.targetColor[3], AUConfig.drawsettings.targetColor[4]))
		end
		if AUConfig.drawsettings.drawflashrange then
			DrawCircle(myHero.x, myHero.y, myHero.z, 1000, ARGB(AUConfig.drawsettings.flashColor[1], AUConfig.drawsettings.flashColor[2], AUConfig.drawsettings.flashColor[3], AUConfig.drawsettings.flashColor[4]))
		end
		if AUConfig.drawsettings.drawharassrange then
			DrawCircle(myHero.x, myHero.y, myHero.z, 650, ARGB(AUConfig.drawsettings.harassColor[1], AUConfig.drawsettings.harassColor[2], AUConfig.drawsettings.harassColor[3], AUConfig.drawsettings.harassColor[4]))
		end
		if AUConfig.drawsettings.drawaarange then
			DrawCircle(myHero.x, myHero.y, myHero.z, 765.5, ARGB(AUConfig.drawsettings.aaColor[1], AUConfig.drawsettings.aaColor[2], AUConfig.drawsettings.aaColor[3], AUConfig.drawsettings.aaColor[4]))
		end
	end


	if AUConfig.drawsettings.drawstun and stunSprite ~= nil then
		if stunReady then 
			local barPos = GetUnitHPBarPos(myHero)
		    local barPosOffset = GetUnitHPBarOffset(myHero)
			local point = WorldToScreen(D3DXVECTOR3(myHero.x, myHero.y, myHero.z))
			if OnScreen(barPos.x, barPos.y) then stunSprite:Draw(barPos.x+64, barPos.y-barPosOffset.y-37, 255) end
		end
	end

	local function dtext(text, person)
		if person == nil then
			if ts.target ~= nil then 
				if AUConfig.drawsettings.fix then 
					DrawText3D(text, ts.target.x, ts.target.y+100, ts.target.z, 20, ARGB(255,255,255,255), true) 
				else
					PrintFloatText(ts.target, 0, text) 
				end
			end
		elseif person ~= nil then
			if AUConfig.drawsettings.fix then 
				DrawText3D(text, person.x, person.y+100, person.z, 20, ARGB(255,255,255,255), true) 
			else
				PrintFloatText(person, 0, text) 
			end
		end
	end

	--DrawText3D(text, myHero.x, myHero.y+100, myHero.z, 20, ARGB(255,255,255,255), true)

	if AUConfig.drawsettings.drawtibbers and tibbersAlive then dtext(""..math.floor(tibbersTimer - GetGameTimer()).."", myHero) end


	if ts.target ~= nil and canKill(ts.target, 6) and ValidTarget(ts.target, 625) and myHero.canMove and not myHero.isTaunted and not myHero.isFeared then dtext("Killable: AA")
		elseif ts.target ~= nil and canKill(ts.target, 3) and AUConfig.drawsettings.drawkillable and qready then dtext("Killable: Q")
		elseif ts.target ~= nil and canKill(ts.target, 4) and not QKillCasted and AUConfig.drawsettings.drawkillable and wready  then dtext("Killable: W")
		elseif ts.target ~= nil and canKill(ts.target, 2) and AUConfig.drawsettings.drawkillable and qready and wready then dtext("Killable: W-Q")
		elseif ts.target ~= nil and canKill(ts.target, 5) and AUConfig.drawsettings.drawkillable and rready and not ts.target.canMove and not wready and not qready and ultAvailable and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R")
		elseif canKill(ts.target, 7) and AUConfig.drawsettings.drawkillable and qready and not wready and ultAvailable and not ts.target.canMove and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R-Q")
		elseif canKill(ts.target, 8) and AUConfig.drawsettings.drawkillable and wready and not qready and ultAvailable and not ts.target.canMove and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R-W")
		elseif canKill(ts.target, 1.1) and AUConfig.drawsettings.drawkillable and qready and wready and ultAvailable and not ts.target.canMove and ValidTarget(ts.target, 620) then dtext("Killable: (Stunned) R-W-Q")
		elseif ts.target ~= nil and canKill(ts.target, 1.1) and AUConfig.drawsettings.drawkillable and qready and wready and rready and not ((stunReady or (pyroStack == 3 and eready))) and ultAvailable then dtext("Killable: (No-Stun) R-W-Q")
		elseif ts.target ~= nil and canKill(ts.target, 1) and AUConfig.drawsettings.drawkillable and qready and wready and rready and (stunReady or (pyroStack == 3 and eready)) and ultAvailable then dtext("Killable: (Stun) R-W-Q") 
	end
end

function OnTick()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	ts:update()
	--ts.target = Selector.GetTarget(LESSCASTADVANCED, AP)
	if ts.target ~= nil then SOWi:ForceTarget(ts.target) end
	qready = (myHero:CanUseSpell(_Q) == READY)
	if AUConfig.farmsettings.qfarm then enemyMinions:update() end
	wready = (myHero:CanUseSpell(_W) == READY)
	eready = (myHero:CanUseSpell(_E) == READY)
	rready = (myHero:CanUseSpell(_R) == READY)
	qmana = myHero:GetSpellData(_Q).mana
	wmana = myHero:GetSpellData(_W).mana
	emana = myHero:GetSpellData(_E).mana
	rmana = myHero:GetSpellData(_R).mana
	dfgslot = GetInventorySlotItem(3128)
	dfgready = (dfgslot ~= nil and myHero:CanUseSpell(dfgslot) == READY)
	bftslot = GetInventorySlotItem(3188)
	bftready = (bftslot ~= nil and myHero:CanUseSpell(bftslot) == READY)
	fqcslot = GetInventorySlotItem(3092)
	fqcready = (fqcslot ~= nil and myHero:CanUseSpell(fqcslot) == READY)
	flashready = (flash ~= nil and myHero:CanUseSpell(flash) == READY)
	igniteready = (ignite ~= nil and myHero:CanUseSpell(ignite) == READY)
	hxtslot = GetInventorySlotItem(3146)
	hxtready = (hxtslot ~= nil and myHero:CanUseSpell(hxtslot) == READY)
	blgslot = GetInventorySlotItem(3144)
	blgready = (blgslot ~= nil and myHero:CanUseSpell(blgslot) == READY)
	odvslot = GetInventorySlotItem(3023)
	odvready = (odvslot ~= nil and myHero:CanUseSpell(odvslot) == READY)

	


	MMAloaded = _G.MMA_Loaded
	SACloaded = _G.AutoCarry and _G.AutoCarry.MyHero
	_G.hasOrbwalker = (SACloaded or MMAloaded or AUConfig.orbwalker.Enabled)
	
	local shouldntAA = (qready or wready) and ((AUConfig.combosettings.screwAA and AUConfig.combosettings.comboActive) or (AUConfig.harasssettings.screwAA and AUConfig.harasssettings.harass))
	if SACloaded and not shouldntAA then _G.AutoCarry.MyHero:AttacksEnabled(true) end
	if MMAloaded and not shouldntAA then EnableMMA() end
	if AUConfig.orbwalker.Enabled and not shouldntAA then SOWi:EnableAttacks() end

	
	attackspeed = 0.579 * myHero.attackSpeed
	
	if ts.target ~= nil and ts.target.type == myHero.type and QTime ~= nil then 
		QKillCasted = canKill(ts.target, 3) and (os.clock() <= QTime) 
	end
	
	--Check VPrediction version
	if checkVersion then
		if VP.version ~= nil then
			VPVersion = VP.version
			VPVersion = string.gsub(VPVersion, "Version: ", "")
			VPVersion = tonumber(VPVersion)
			if VPVersion < 2.413 then
				print("<font color='#DB0004'>Annie the UnBEARable: You need to update VPrediction to the latest version!</font><")
				print("<font color='#DB0004'>Annie the UnBEARable: You need to update VPrediction to the latest version!</font><")
				print("<font color='#DB0004'>Annie the UnBEARable: You need to update VPrediction to the latest version!</font><")
			end
			checkVersion = false
		end
	end

	
	--Ult Available
	if not tibbersAlive and rready then 
		ultAvailable = true
	else
		ultAvailable = false
	end
	
	--Auto level
	if AUConfig.autolevel then
		local ultLevel = myHero:GetSpellData(_R).level
		local realLevel = GetHeroLeveled()
		
		if player.level > realLevel and (player.level >= 6 and ultLevel == 0) or (player.level >= 11 and ultLevel == 1) or (player.level >= 16 and ultLevel == 2) then
			LevelSpell(_R)
		end
	end
	
	--Damage calculations
	qdamage = math.floor(((((((myHero:GetSpellData(_Q).level-1) * 35) + 80) + .80 * myHero.ap))))
	wdamage = math.floor(((((((myHero:GetSpellData(_W).level-1) * 45) + 70) + .85 * myHero.ap))))
	if stunReady or (pyroStack == 3 and eready) then 
		rdamage = math.floor((((((myHero:GetSpellData(_R).level-1) * 125) + 175) + .80 * myHero.ap)))
	else
		rdamage = math.floor((((((myHero:GetSpellData(_R).level-1) * 125) + 210) + 1 * myHero.ap)))
	end

	local function wHlich()
		if not myHero.dead and lichbane ~= nil and (lichbane.endT < os.clock() + .25) then return true end
		return false
	end

	if ts.target ~= nil then 
		if wHlich() then
			AAdamage = myHero:CalcMagicDamage(ts.target, (myHero.damage + 71 + (.5 * myHero.ap)))
		else
			AAdamage = myHero:CalcDamage(ts.target, myHero.damage)
		end
	else
		AAdamage = myHero.damage
	end
	
	--Random spawn stacker
	spawnStacker()
	
	
	--Update TS range if flash available
	if flashready and qready and wready and ultAvailable and ((stunReady or (pyroStack == 3 and eready))) then
		ts.range = 1000
		ts:update()
	elseif AUConfig.harasssettings.harass then
		ts.range = 1000
		ts:update()
	elseif not (qready or wready) then
		ts.range = 675.5
		ts:update()
	else
		ts.range = 620
		ts:update()
	end

	if farmTurnedOff and not AUConfig.combosettings.comboActive and AUConfig.farmsettings.reactivate then farmTurnedOff = false; AUConfig.farmsettings.qfarm = true end
	--Stuff
	Combo()
	AutoQFarm()
	Harass()
	CommandBear()
	AutoStun()

		
	
	--Karthus stuff
	if karthusUltTime ~= nil and karthus ~= nil then
		if os.clock() + GetLatency()/2000 > karthusUltTime + 2.2 and ValidTarget(karthus, 625) then
			if AUConfig.stunsettings.defensiveult.comboUlt and hasMana(7) and qready and wready and ultAvailable and stunReady then
				doCombo(7, karthus)
			elseif AUConfig.stunsettings.defensiveult.comboUlt and hasMana(1) and qready and wready and eready and ultAvailable and pyroStack == 3 then
				doCombo(1, karthus)
			elseif (qready or wready) and stunReady then
				if qready then CastExploit(_Q, karthus) elseif wready then CastW(target) end
			elseif qready and eready and pyroStack == 3 then
				CastSpell(_E)
				if qready then CastExploit(_Q, karthus) elseif wready then CastW(target) end
			end
			karthusUltTime = nil
			karthus = nil
		elseif os.clock() + GetLatency()/2000 > karthusUltTime + 2.2 and eready then
			CastSpell(_E)
		end
	end	
end


function DisableMMA()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	_G.MMA_Orbwalker = false
	_G.MMA_HybridMode = false
	_G.MMA_LaneClear = false
	_G.MMA_LastHit = false
	_G.MMA_Target = nil
end
function EnableMMA()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	--[[
	_G.MMA_Orbwalker = true
	_G.MMA_HybridMode = true
	_G.MMA_LaneClear = true
	_G.MMA_LastHit = true
	]]
end


function Combo()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	--Stop SAC/MMA/SOW from auto attacking when target is out of combo range and spells are ready
	if AUConfig.combosettings.comboActive then
		local shouldntAA = (qready or wready) and AUConfig.combosettings.screwAA

		if not hasOrbwalker and AUConfig.combosettings.movetomouse then
			myHero:MoveTo(mousePos.x, mousePos.z)
		elseif hasOrbwalker and shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(false) end
			if MMAloaded then DisableMMA() end
			if AUConfig.orbwalker.Enabled then SOWi:DisableAttacks() end
			if AUConfig.combosettings.movetomouse then myHero:MoveTo(mousePos.x, mousePos.z) end
		elseif hasOrbwalker and not shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(true) end
			if MMAloaded then EnableMMA() end
			if AUConfig.orbwalker.Enabled then SOWi:EnableAttacks() end

			if SACloaded then
				_G.AutoCarry.MyHero:AttacksEnabled(true)
			elseif MMALoaded and _G.MMA_AbleToMove and AUConfig.combosettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			elseif AUConfig.orbwalker.Enabled and SOWi:CanMove() and AUConfig.combosettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			end
		end
	end


	if ts.target ~= nil and AUConfig.combosettings.comboActive then
		if string.find(ts.target.type, "obj_AI_Minion") and ts.target.team ~= myHero.team then
			if ValidTarget(ts.target, 625) then CastW(ts.target) end
			if ValidTarget(ts.target, 625) then CastExploit(_Q, ts.target)  end
		end
	end

	if ts.target ~= nil and ts.target.team ~= myHero.team and ts.target.type == myHero.type then
		if AUConfig.combosettings.comboActive then
			if AUConfig.farmsettings.qfarm then
				farmTurnedOff = true
				AUConfig.farmsettings.qfarm = false
			end
			if canKill(ts.target, 3) and hasMana(3) and ValidTarget(ts.target, 620) and qready then doCombo(3, ts.target)
			elseif canKill(ts.target, 4) and hasMana(4) and wready and ValidTarget(ts.target, 620) then doCombo(4, ts.target) -- low range so W will hit
			elseif canKill(ts.target, 2) and hasMana(2) and qready and wready and ValidTarget(ts.target, 620) then doCombo(2, ts.target) --also low range so W will hit
			elseif canKill(ts.target, 7) and hasMana(8) and not canKill(ts.target, 3) and qready and not wready and ultAvailable and ValidTarget(ts.target, 620) then doCombo(8, ts.target)
			elseif canKill(ts.target, 8) and wreadyand and not canKill(ts.target, 4) and hasMana(11) and not qready and ultAvailable and ValidTarget(ts.target, 620) then doCombo(11, ts.target)
			elseif canKill(ts.target, 1.1) and hasMana(7) and qready and wready and ultAvailable and ValidTarget(ts.target, 620) then doCombo(7, ts.target)
			elseif canKill(ts.target, 1.1) and hasMana(7) and qready and wready and rready and not ((stunReady or (pyroStack == 3 and eready))) and ValidTarget(ts.target, 620) and ultAvailable then doCombo(7, ts.target)  --also low range so W will hit
			elseif canKill(ts.target, 1) and qready and wready and rready and ValidTarget(ts.target, 620) and ultAvailable and not canKill(ts.target, 2) then 
				if pyroStack == 3 and eready and hasMana(1) then
					doCombo(1, ts.target)
					elseif stunReady and hasMana(7) then
					doCombo(7, ts.target)
				end
			elseif canKill(ts.target, 1) and not canKill(ts.target, 2) and qready and wready and rready and (GetDistanceSqr(myHero.visionPos, ts.target.visionPos) > 562500 and GetDistanceSqr(myHero.visionPos, ts.target.visionPos) < 990025) and ValidTarget(ts.target, 995) and not tibbersAlive and AUConfig.combosettings.useflash and flashready then
				if pyroStack == 3 and eready and hasMana(5) then
					doCombo(5, ts.target)
				elseif stunReady and hasMana(6) then
					doCombo(6, ts.target)
				end
			elseif canKill(ts.target, 5) and ultAvailable and not wready and not qready and ValidTarget(ts.target, 620) and (VP.TargetsImmobile[ts.target.networkID] and VP.TargetsImmobile[ts.target.networkID] > os.clock() + .25 + GetLatency()/2000) then CastR(ts.target)
			end
			if ValidTarget(ts.target, 620) then
				--Check if enemy already stunned (if they are, then do full combo if ult available)

				--if not canKill target and q or w ready
				if hasMana(1) and qready and wready and eready and ultAvailable and pyroStack == 3 and not didCombo then  --Combo at 3 stacks 
					doCombo(1, ts.target)
				elseif hasMana(7) and qready and wready and stunReady and ultAvailable and not didCombo then
					doCombo(7, ts.target)
				end
				if ultAvailable and hasMana(12) and ((stunReady or (pyroStack == 3 and eready))) and not didCombo then---------------------------------------
					--if myHero:GetSpellData(_W).currentCd > 1.2 then doCombo(12, ts.target) end
					if not wready and qready and hasMana(12) then doCombo(12, ts.target) end
				end-----------------------------------------------------------------------------------------------------------------------------------------
				--[[
				if not ts.target.canMove and ((qready and not canKill(ts.target, 3) or (wready and not canKill(ts.target, 4)))) and ultAvailable and not didCombo then
					if hasMana(12) and qready and not wready then doCombo(8, ts.target)
						elseif hasMana(11) and wready and not qready then doCombo(11, ts.target)
						elseif hasMana(7) and qready and wready then doCombo(7, ts.target)
					end
				end
				]]
				if (VP.TargetsImmobile[ts.target.networkID] and VP.TargetsImmobile[ts.target.networkID] > os.clock() + .25 + GetLatency()/2000) and ultAvailable and not didCombo then
					if hasMana(12) and qready and not wready then doCombo(8, ts.target)
						elseif hasMana(11) and wready and not qready then doCombo(11, ts.target)
						elseif hasMana(7) and qready and wready then doCombo(7, ts.target)
						elseif hasMana(13) and not qready and not wready then doCombo(13, ts.target)
					end
				end
				--No wasting of pyrostacks
				if pyroStack < 4 and not eready and ultAvailable and not didCombo then
					if hasMana(3) and qready and not wready then
						doCombo(3, ts.target)
					elseif hasMana(4) and wready and not qready then
						doCombo(4, ts.target)
					end
					elseif pyroStack < 3 and not eready and ultAvailable and not didCombo then
					if hasMana(3) and qready and not wready then
						doCombo(3, ts.target)
					elseif hasMana(4) and wready and not qready then
						doCombo(4, ts.target)
					end
				end

				if ultAvailable and ((stunReady or (pyroStack == 3 and eready))) then --so full combo isn't fucked over by a small combo
					didCombo = true
				end
				if hasMana(2) and qready and wready and not didCombo --[[and not ultAvailable]] then 
					doCombo(2, ts.target)
				elseif hasMana(3) and qready and not wready and not didCombo then
					doCombo(3, ts.target)
				elseif hasMana(4) and wready and not qready and not didCombo then -- reduced range for better accuracy/less flops
					doCombo(4, ts.target)
				end
				didCombo = false
			end
		end
	end
end


local enemiesStunned = 0

function AutoStun()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if AUConfig.stunsettings.autoStunW and wready and not AUConfig.combosettings.comboActive and (stunReady or (pyroStack == 3 and eready)) and ts.target ~= nil then --avoid bugsplat so Combo does not conflict
		local wpos, hitChance, numberOfEnemiesInCone = VP:GetConeAOECastPosition(ts.target, .25, 24.76, 620, math.huge, myHero)
		if (wpos ~= nil and numberOfEnemiesInCone ~= nil) and (numberOfEnemiesInCone >= AUConfig.stunsettings.autoStunWvalue) and hitChance >= 2 then
			if hasMana({'E', 'W'}) and pyroStack == 3 and eready then
				CastSpell(_E)
				Packet("S_CAST", {spellId = _W, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			elseif hasMana({'W'}) and stunReady then
				Packet("S_CAST", {spellId = _W, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			end
		end
	elseif AUConfig.stunsettings.autoStunR and ultAvailable and not AUConfig.combosettings.comboActive and (stunReady or (pyroStack == 3 and eready)) and ts.target ~= nil then
		local wpos, hitChance, numberOfEnemiesInCone = VP:GetCircularAOECastPosition(ts.target, .25, 250, 850, math.huge, myHero)
		if (wpos ~= nil and numblerOfEnemiesInCone ~= nil) and (numberOfEnemiesInCone >= AUConfig.stunsettings.autoStunWvalue) and hitChance >= 3 then
			if hasMana({'E', 'R'}) and pyroStack == 3 and eready then
				CastSpell(_E)
				Packet("S_CAST", {spellId = _R, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			elseif hasMana({'R'}) and stunReady then
				Packet("S_CAST", {spellId = _R, toX = wpos.x, toY = wpos.z, fromX = wpos.x, fromY = wpos.z}):send()
			end
		end
	end
	if AUConfig.stunsettings.autoStunCombo and (not wready or not ultAvailable) and (AUConfig.stunsettings.autoStunR or AUConfig.stunsettings.autoStunW) and ts.target ~= nil then
		for i=1, heroManager.iCount do
			local enemy = heroManager:getHero(i)
			if enemy.team == TEAM_ENEMY and (VP.TargetsImmobile[ts.target.networkID] and VP.TargetsImmobile[ts.target.networkID] > os.clock() + GetLatency()/2000) and qready and rready and not tibbersAlive and not didCombo and GetDistanceSqr(myHero.visionPos, enemy.visionPos) < 360000 then
				enemiesStunned = enemiesStunned + 1
			end
		end
		if enemiesStunned >= AUConfig.stunsettings.autoStunWvalue and hasMana(8) and qready and rready and not tibbersAlive and not didCombo then
			doCombo(8, ts.target)
			enemiesStunned = 0
		elseif enemiesStunned >= AUConfig.stunsettings.autoStunWvalue and not didCombo and not ultAvailable then
			if qready and wready and hasMana(2) then doCombo(2, ts.target)
				elseif not qready and wready and hasMana(4) then doCombo(4, ts.target)
				elseif qready and not wready and hasMana(3) then doCombo(3, ts.target)
			end
		end
		enemiesStunned = 0
	end
end


--Auto stun under tower
function OnTowerFocus(tower, unit)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if unit ~= nil then
		if AUConfig.stunsettings.autoStunTower and (qready or wready) and (stunReady or (pyroStack == 3 and eready)) and unit.team == TEAM_ENEMY and string.lower(unit.type) == "obj_ai_hero" then
			if pyroStack == 4 and qready and ValidTarget(unit, 625) and UnderTurret(unit, false) then
				CastExploit(_Q, unit)
			elseif pyroStack == 3 and qready and eready and hasMana({'E', 'Q'}) and ValidTarget(unit, 625) and UnderTurret(unit, false) then
				CastSpell(_E)
				CastExploit(_Q, unit)
			elseif not qready and wready and pyroStack == 4 and GetDistanceSqr(myHero.visionPos, unit.visionPos) < 372100 then
				CastW(unit)
			elseif not qready and wready and eready and hasMana({'E', 'W'}) and pyroStack == 3 and GetDistanceSqr(myHero.visionPos, unit.visionPos) < 372100 then
				CastSpell(_E)
				CastW(unit)
			end
		end
	end
end


function Harass() -- or AUConfig.orbwalker.Enabled
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if AUConfig.harasssettings.harass then
		local shouldntAA = (qready or wready) and AUConfig.harasssettings.screwAA

		if not hasOrbwalker and AUConfig.harasssettings.movetomouse then
			myHero:MoveTo(mousePos.x, mousePos.z)
		elseif hasOrbwalker and shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(false) end
			if MMAloaded then DisableMMA() end
			if AUConfig.orbwalker.Enabled then SOWi:DisableAttacks() end
			if AUConfig.harasssettings.movetomouse then myHero:MoveTo(mousePos.x, mousePos.z) end
		elseif hasOrbwalker and not shouldntAA then
			if SACloaded then _G.AutoCarry.MyHero:AttacksEnabled(true);_G.AutoCarry.Orbwalker:Orbwalk(ts.target) end
			if MMAloaded then EnableMMA();_G.MMA_Orbwalker = true end
			if AUConfig.orbwalker.Enabled then SOWi:EnableAttacks();SOWi:OrbWalk(ts.target) end

			if SACloaded then
				_G.AutoCarry.MyHero:AttacksEnabled(true)
			elseif MMAloaded and _G.MMA_AbleToMove and AUConfig.harasssettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			elseif AUConfig.orbwalker.Enabled and SOWi:CanMove() and AUConfig.harasssettings.movetomouse then
				myHero:MoveTo(mousePos.x, mousePos.z)
			end
		end

		--Use W (checks if allowed to stun)
		if AUConfig.harasssettings.harassW and AUConfig.harasssettings.harassStun and ValidTarget(ts.target, 625) and wready then CastW(ts.target)
		elseif AUConfig.harasssettings.harassW and not AUConfig.harasssettings.harassStun and not stunReady and wready and ValidTarget(ts.target, 625) then CastW(ts.target) end
		--Use Q (checks if allowed to stun)
		if AUConfig.harasssettings.harassQ and AUConfig.harasssettings.harassStun and ValidTarget(ts.target, 625) and qready then CastExploit(_Q, ts.target) 
		elseif AUConfig.harasssettings.harassQ and not AUConfig.harasssettings.harassStun and not stunReady and ValidTarget(ts.target, 625) and qready then CastExploit(_Q, ts.target) end	

		--if MMAloaded then DisableMMA() end
	end
end




function AutoQFarm()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if AUConfig.farmsettings.qfarm and qready then
		if AUConfig.farmsettings.qfarmstop1 then
			if pyroStack ~= 4 then
				for index, minion in pairs(enemyMinions.objects) do
					MinionPredict(minion)
				end
			end
		elseif not AUConfig.farmsettings.qfarmstop1 then
			for index, minion in pairs(enemyMinions.objects) do
				MinionPredict(minion)
			end
		end
	end
end



function MinionPredict(minion)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	local distanceM = GetDistance(minion.visionPos, myHero.visionPos)
	local time = .25 + distanceM / 1400 - 0.07
	local PredictedHealth = VP:GetPredictedHealth(minion, time)
	if qready and minion ~= nil and PredictedHealth <= myHero:CalcMagicDamage(minion, qdamage) and distanceM <= 625 then
		if ValidTarget(minion, 620) then
			CastExploit(_Q, minion) 
		end
	end
end

function CommandBear()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if ts.target ~= nil and tibbersAlive and AUConfig.tibbers.directTibbers then
		local target = ts.target
		local hitboxSqr = VP:GetHitBox(target) + 80 -- 80 = tib hitbox
		hiboxSqr = hitboxSqr * hitboxSqr --since we are using GetDistanceSqr
		if GetDistanceSqr(tibbersObject.visionPos, target.visionPos) < 15625 + hitboxSqr then
			Packet("S_MOVE", {type = 5, dwArg1 = 1, dwArg2 = 0, sourceNetworkId = myHero.networkID, unitNetworkId = tibbersObject.networkID, targetNetworkId = target.networkID, x = ts.target.visionPos.x, y = ts.target.visionPos.z}):send()
		elseif GetDistanceSqr(tibbersObject.visionPos, target.visionPos) > 15625 + hitboxSqr then
			Packet("S_MOVE", {type = 6, dwArg1 = 1, dwArg2 = 0, sourceNetworkId = tibbersObject.networkID, unitNetworkId = tibbersObject.networkID, targetNetworkId = 0, waypoint = 1, x = ts.target.visionPos.x, y = ts.target.visionPos.z}):send()
		end
	elseif tibbersAlive and AUConfig.tibbers.followTibbers and not myHero.dead then
		if GetDistanceSqr(tibbersObject.visionPos, myHero.visionPos) > 15625 then Packet("S_MOVE", {type = 6, dwArg1 = 1, dwArg2 = 0, sourceNetworkId = tibbersObject.networkID, unitNetworkId = tibbersObject.networkID, targetNetworkId = 0, waypoint = 1, x = myHero.visionPos.x, y = myHero.visionPos.z}):send() end
	end
end


function CastExploit(spell, target)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if AUConfig.castsettings.spellexploit and target ~= nil and (((ValidTarget(ts.target, 4000) and target.type == myHero.type)) or target.type ~= myHero.type) then 
		Packet("S_CAST", {spellId = spell, targetNetworkId = target.networkID}):send()
		QTime = os.clock() + (.25 + GetDistance(target.visionPos, myHero.visionPos) / 1400)
		--Packet("S_CAST", {spellId = spell, toX = target.x, toY = target.z, targetNetworkId = target.networkID}):send()
	elseif target ~= nil then
		CastSpell(spell, target)
		QTime = os.clock() + (.25 + GetDistance(target.visionPos, myHero.visionPos) / 1400)
	end
end
--function VPrediction:GetCircularAOECastPosition(unit, delay, radius, range, speed, from)
function CastR(target)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if not QKillCasted then
		if AUConfig.castsettings.method1 and not AUConfig.castsettings.method2 then 
			spellPos = VP:GetCircularAOECastPosition(target, .25, 250, 600, math.huge, myHero)
		elseif AUConfig.castsettings.method2 and not AUConfig.castsettings.method1 then
			spellPos = nil
		else -- if neither are selected, then default
			spellPos = VP:GetCircularAOECastPosition(target, .25, 250, 600, math.huge, myHero)
		end
		

		if spellPos ~= nil then
			--CastSpell(_R, spellPos.x, spellPos.z)
			Packet("S_CAST", {spellId = _R, toX = spellPos.x, toY = spellPos.z, fromX = spellPos.x, fromY = spellPos.z}):send()
		else
			Packet("S_CAST", {spellId = _R, toX = target.visionPos.x, toY = target.visionPos.z, fromX = target.visionPos.x, fromY = target.visionPos.z}):send()
		end
	end
end


function CastW(target)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if not QKillCasted then
		if AUConfig.castsettings.method1 and not AUConfig.castsettings.method2 then 
			--VPrediction:GetCircularAOECastPosition(unit, delay, radius, range, speed, from)
			----------------------------------------------
			--VPrediction:GetConeAOECastPosition(unit, delay, angle, range, speed, from)
			spellPos = VP:GetConeAOECastPosition(target, .25, 24.76, 620, math.huge, myHero)
		elseif AUConfig.castsettings.method2 and not AUConfig.castsettings.method1 then
			spellPos = nil
		else -- if neither are selected, then default
			spellPos = VP:GetConeAOECastPosition(target, .25, 24.76, 620, math.huge, myHero)
		end

		
		if spellPos ~= nil and not AUConfig.castsettings.antimiss then --308025
			--CastSpell(_R, spellPos.x, spellPos.z)
			Packet("S_CAST", {spellId = _W, toX = spellPos.x, toY = spellPos.z, fromX = spellPos.x, fromY = spellPos.z}):send()
		elseif AUConfig.castsettings.antimiss and spellPos ~= nil and not (not isFacing(target, myHero, 400) and GetDistanceSqr(myHero.visionPos, target.visionPos) >= 360000 and target.canMove and not VP:isSlowed(ts.target, .25, 625, myHero)) then
			Packet("S_CAST", {spellId = _W, toX = spellPos.x, toY = spellPos.z, fromX = spellPos.x, fromY = spellPos.z}):send()
		elseif spellPos == nil then
			Packet("S_CAST", {spellId = _W, toX = target.visionPos.x, toY = target.visionPos.z, fromX = target.visionPos.x, fromY = target.visionPos.z}):send()
		end
	end
end

function hasMana(combo)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	local totalMana = 0
	if type(combo) == 'number' then
		if combo == 1 then
			totalMana = emana + rmana + wmana + qmana
		end
		if combo == 2 then
			totalMana = wmana + qmana
		end
		if combo == 3 then
			totalMana = qmana
		end
		if combo == 4 then
			totalMana = wmana
		end
		if combo == 5 then
			totalMana = emana + rmana + wmana + qmana
		end
		if combo == 6 then
			totalMana = rmana + wmana + qmana
		end
		if combo == 7 then
			totalMana = rmana + wmana + qmana
		end
		if combo == 8 then
			totalMana = rmana + qmana
		end
		if combo == 9 then
			totalMana = emana + rmana + qmana
		end
		if combo == 10 then
			totalMana = rmana + wmana + qmana
		end
		if combo == 11 then
			totalMana = rmana + wmana
		end
		if combo == 12 then
			totalMana = rmana + qmana
		end
		if combo == 13 then
			totalMana = rmana
		end
	elseif type(combo) == 'table' then
		for i=1, #combo do
			if combo[i] == 'Q' then totalMana = totalMana + qmana end
			if combo[i] == 'W' then totalMana = totalMana + wmana end
			if combo[i] == 'E' then totalMana = totalMana + emana end
			if combo[i] == 'R' then totalMana = totalMana + rmana end
		end
	end
	return totalMana <= myHero.mana
end
function doCombo(combo, target)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	local function ItemSpamCast(target)
		if fqcready then CastExploit(fqcslot, target) end
		if hxtready then CastExploit(hxtslot, target) end
		if blgready then CastExploit(blgslot, target) end
		if odvready then CastSpell(odvslot) end
	end

	local candfg = (dfgready or bftready) and ts.target ~= nil and ts.target.visionPos ~= nil and (GetDistanceSqr(ts.target.visionPos, myHero.visionPos) <= 384400)

	if combo == 1 then --Full combo (no flash) at 3 stacks
		CastSpell(_E)
		if dfgready and candfg then CastExploit(dfgslot, target) end
		if bftready and candfg then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 2 then -- Q and W
		CastW(target)
		CastExploit(_Q, target)
		didCombo = true
	end
	if combo == 3 then -- Q
		CastExploit(_Q, target)
		didCombo = true
	end
	if combo == 4 then -- W
		CastW(target)
		didCombo = true
	end
	if combo == 5 then -- 3 stacks + flash + full combo
		CastSpell(_E)
		CastSpell(flash, target.visionPos.x, target.visionPos.z)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 6 then -- stun ready + flash + full combo
		CastSpell(flash, target.visionPos.x, target.visionPos.z)
		if dfgready then CastExploit(dfgslot, target) end
		if bftready then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 7 then --Full combo (no flash) with stun ready
		if dfgready and candfg then CastExploit(dfgslot, target) end
		if bftready and candfg then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 8 then -- after auto stun
		if dfgready and candfg then CastExploit(dfgslot, target) end
		if bftready and candfg then CastExploit(bftslot, target) end
		CastR(target)
		CastExploit(_Q, target) 
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 9 then -- after auto stun, 3 stacks pyromania
		if dfgready and candfg then CastExploit(dfgslot, target) end
		if bftready and candfg then CastExploit(bftslot, target) end
		CastSpell(_E)
		CastR(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 10 then --after defensive ult
		if dfgready and candfg then CastExploit(dfgslot, target) end
		if bftready and candfg then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		--tibbersAlive = true
		--stunReady = false
		didCombo = true
	end
	if combo == 11 then
		if dfgready and candfg then CastExploit(dfgslot, target) end
		if bftready and candfg then CastExploit(bftslot, target) end
		CastR(target)
		CastW(target)
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 12 then
		if dfgready and candfg then CastExploit(dfgslot, target) end
		if bftready and candfg then CastExploit(bftslot, target) end
		CastR(target)
		CastExploit(_Q, target)
		ItemSpamCast(target)
		didCombo = true
	end
	if combo == 13 then
		if dfgready and candfg then CastExploit(dfgslot, target) end
		if bftready and candfg then CastExploit(bftslot, target) end
		CastR(target)
		ItemSpamCast(target)
		didCombo = true
	end
end

--the canKill combo # may not correspond with the doCombo combo #

function canKill(target, combo)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if (combo == 1 or combo == 1.1) and target ~= nil then
		comboDamage = qdamage + wdamage + rdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*target.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*target.health)) + (.20*comboDamage)
		end
		if AUConfig.combosettings.ignite and igniteready then
			local ignitedmg = 50 + (20 * myHero.level)
			return myHero:CalcMagicDamage(target, comboDamage) + ((hasOrbwalker and AAdamage) or 0) + ignitedmg > target.health

		else
			return myHero:CalcMagicDamage(target, comboDamage) + ((hasOrbwalker and AAdamage) or 0) > target.health
		end

	elseif combo == 2 and target ~= nil then
		comboDamage = qdamage + wdamage
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 3 and target ~= nil then
		comboDamage = qdamage
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 4 and target ~= nil then
		comboDamage = wdamage 
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 5 and target ~= nil then
		comboDamage = rdamage 
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 6 and target ~= nil then
		return AAdamage > target.health

	elseif combo == 7 and target ~= nil then
		comboDamage = qdamage + rdamage
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	elseif combo == 8 and target ~= nil then
		comboDamage = wdamage + rdamage
		return myHero:CalcMagicDamage(target, comboDamage) > target.health

	end
end


--Feez made this script and is sexy

function spawnStacker()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if AUConfig.passive.passiveStack then 
		if GetDistanceSqr(myHero.visionPos, spawnTurret.visionPos) < 1960000 and spawnTurret ~= nil and not isRecalling then
			if pyroStack < 4 and eready then 
				CastSpell(_E)
			end
			if pyroStack < 4 and wready and not isRecalling then
				CastSpell(_W, myHero.visionPos.x + 50, myHero.visionPos.z + 50)
			end
		end
	end
	if AUConfig.passive.passiveStack2 and not isRecalling and pyroStack < 4 then
		if eready then
			CastSpell(_E)
		end
	end
end

function OnCreateObj(object)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if object.team ~= TEAM_ENEMY and object.name == "Tibbers" then
		tibbersObject = object
	end
end

local notAA = {
	['shyvanadoubleattackdragon'] = true,
	['shyvanadoubleattack'] = true,
	['monkeykingdoubleattack'] = true,
}

function OnProcessSpell(unit, spellProc)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
		--print("Unit: "..unit.charName.. " , Spell: "..spellProc.name.." , Delay: "..spellProc.windUpTime.." , Animation Time: "..spellProc.animationTime)
		--Defensive Stun--
	if AUConfig.stunsettings.defensiveult.stunUlt and ((stunReady or (pyroStack == 3 and eready))) and not isRecalling and myHero.canMove then
		if unit.team == TEAM_ENEMY and ultTable[unit.charName] ~= nil and ultTable[unit.charName].spell ~= nil and AUConfig.stunsettings.defensiveult[unit.charName] ~= nil and not spellProc.name == "FallenOne" then
			if spellProc.name == ultTable[unit.charName].spell and AUConfig.stunsettings.defensiveult[unit.charName] then
				if ((stunReady or (pyroStack == 3 and eready))) and ValidTarget(unit, 620) then 
					if AUConfig.stunsettings.defensiveult.comboUlt and qready and wready and ultAvailable and stunReady and hasMana(7) then
						doCombo(7, unit)
						elseif AUConfig.stunsettings.defensiveult.comboUlt and qready and wready and eready and ultAvailable and pyroStack == 3 and hasMana(1) then
						doCombo(1, unit)
						elseif (qready or wready) and stunReady then
						if qready then CastExploit(_Q, unit) elseif wready then CastW(target) end
						elseif qready and eready and pyroStack == 3 then
						CastSpell(_E)
						if qready then CastExploit(_Q, unit) elseif wready then CastW(target) end
					end
				end
			end
		elseif unit.team == TEAM_ENEMY and ultTable[unit.charName] ~= nil and ultTable[unit.charName].spell ~= nil and AUConfig.stunsettings.defensiveult[unit.charName] ~= nil and spellProc.name == "FallenOne" then
			if spellProc.name == ultTable[unit.charName].spell and AUConfig.stunsettings.defensiveult[unit.charName] then
				karthusUltTime = os.clock() - GetLatency()/2000 
				karthus = unit
			end
		end
	end
	--Auto Shield--
	if AUConfig.autoshield.enabled and unit.team ~= myHero.team and spellProc.target == myHero and eready and (string.find(string.lower(spellProc.name), "attack") and (not notAA[string.lower(spellProc.name)]) and not string.find(string.lower(spellProc.name), "minion") or (shieldTable[spellProc.name] ~= nil)) then
		CastSpell(_E)
	end
	
	--[[
	if (spellProc.name == "annietibbersBasicAttack") or (spellProc.name == "annietibbersBasicAttack2") or string.find(spellProc.name, "annietibbersBasic") and unit.team == myHero.team then
	tibLA = os.clock() - GetLatency()/2000
	tibWind = spellProc.windUpTime
	tibAnimation = spellProc.animationTime
	target = ts.target
	--print("ATACKEDDDDDDDDDDDDDDDDDDDDDDDDD")
	end
	]]
end

function OnAnimation(unit, animation)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if unit.isMe and string.lower(animation) == "recall" then isRecalling = true end
	if unit.isMe and string.lower(animation) == "recall_winddown" then isRecalling = false end
	--if unit.isMe then print(animation) end
end
--Now uses advanced callbacks to check pyromania stacks and if tibbers is alive (VIP Only)

function OnGainBuff(unit, buff)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if buff.name == "pyromania" and unit.isMe then
		pyroStack = buff.stack
	end
	if buff.name == "pyromania_particle" and unit.isMe then
		pyroStack = 4
		stunReady = true
	end
	if unit.isMe and buff.name == "infernalguardiantimer" then
		tibbersAlive = true
		tibbersTimer = buff.endT
	end
	if unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinCaptureChannel") then
		isRecalling = true
	end
	if unit.isMe and buff.name == "lichbane" then
		lichbane = buff
	end
end

function OnUpdateBuff(unit, buff)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if buff.name == "pyromania" and unit.isMe then
		pyroStack = buff.stack
	end   
	if unit.isMe and buff.name == "lichbane" then
		lichbane = buff
	end
end


function OnLoseBuff(unit, buff)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if buff.name == "pyromania_particle" and unit.isMe then
		pyroStack = 0
		stunReady = false
	end
	if unit.isMe and buff.name == "infernalguardiantimer" then
		tibbersAlive = false
	end
	if unit.isMe and (buff.name == "Recall" or buff.name == "RecallImproved" or buff.name == "OdinRecallImproved" or buff.name == "OdinRecall" or buff.name == "OdinCaptureChannel") then
		isRecalling = false
	end
	if unit.isMe and buff.name == "lichbane" then
		lichbane = nil
	end
end

--http://botoflegends.com/forum/topic/19669-for-devs-isfacing/
function isFacing(source, target, lineLength)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if source.visionPos ~= nil then
		local sourceVector = Vector(source.visionPos.x, source.visionPos.z)
		local sourcePos = Vector(source.x, source.z)
		sourceVector = (sourceVector-sourcePos):normalized()
		sourceVector = sourcePos + (sourceVector*(GetDistance(target, source)))
		return GetDistanceSqr(target, {x = sourceVector.x, z = sourceVector.y}) <= (lineLength and lineLength^2 or 90000)
	end
end

--[[
local function getDamage(damageSource, enemy)
	if not enemy then return 0 end

	local comboDamage 
	if damageSource == "Q" then
		comboDamage = qdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*enemy.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*enemy.health)) + (.20*comboDamage)
		end
		return myHero:CalcMagicDamage(enemy, comboDamage)
	elseif damageSource == "W" then
		comboDamage = wdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*enemy.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*enemy.health)) + (.20*comboDamage)
		end
		return myHero:CalcMagicDamage(enemy, comboDamage)
	elseif damageSource == "R" then
		comboDamage = rdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*enemy.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*enemy.health)) + (.20*comboDamage)
		end
		return myHero:CalcMagicDamage(enemy, comboDamage)
	elseif damageSource == "Q-W" then
		comboDamage = qdamage + wdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*enemy.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*enemy.health)) + (.20*comboDamage)
		end
		return myHero:CalcMagicDamage(enemy, comboDamage)
	elseif damageSource == "Q-R" then
		comboDamage = qdamage + rdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*enemy.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*enemy.health)) + (.20*comboDamage)
		end
		return myHero:CalcMagicDamage(enemy, comboDamage)
	elseif damageSource == "W-R" then
		comboDamage = wdamage + rdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*enemy.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*enemy.health)) + (.20*comboDamage)
		end
		return myHero:CalcMagicDamage(enemy, comboDamage)
	elseif damageSource == "Q-W-R" then
		comboDamage = qdamage + wdamage + rdamage
		if dfgready then
			comboDamage = (comboDamage + (.15*enemy.health)) + (.20*comboDamage)
		end
		if bftready then
			comboDamage = (comboDamage + (.20*enemy.health)) + (.20*comboDamage)
		end
		return myHero:CalcMagicDamage(enemy, comboDamage)
	end
end
]]