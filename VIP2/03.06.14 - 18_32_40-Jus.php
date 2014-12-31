<?php exit() ?>--by Jus 189.69.16.83
if myHero.charName ~= "Tristana" then Print("<font color=\"#FF0000\"><b>You Need Tristana to Play.</b></font>") return end

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

----------
----------      You change the below values only!
----------      
----------

_ENV[r({100,101,118,110,97,109,101})] = r({74,117,115}) -- devname
_ENV[r({115,99,114,105,112,116,110,97,109,101})] = 'tristanamyelo' -- script name
_ENV[r({115,99,114,105,112,116,118,101,114})] = 1.0 -- version

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
--   if (ur == 'eune') or (ur == 'euw') or (ur == 'tr') or (ur == 'ru')  then 
--     if (g4 == false) then _ENV[r({75,101,107,49,50})](4) return end --EU Server
--     if (g3 == false) then _ENV[r({75,101,107,49,50})](3) return end --US SERVER
--     if (g1 == false) then _ENV[r({75,101,107,49,50})](1) return end --Main Server
--     if (g2 == false) then _ENV[r({75,101,107,49,50})](2) return end --Backup Server
--   end
--   if (ur == 'na') or (ur == 'unk') or (ur == 'br') or (ur == 'oce') or (ur == 'la1') or (ur == 'la2') then 
--     if (g3 == false) then _ENV[r({75,101,107,49,50})](3) return end --US SERVER
--     if (g4 == false) then _ENV[r({75,101,107,49,50})](4) return end --EU Server
--     if (g1 == false) then _ENV[r({75,101,107,49,50})](1) return end --Main Server
--     if (g2 == false) then _ENV[r({75,101,107,49,50})](2) return end --Backup Server
--   end
-- --------------------- Catch all if there are new regions ---------------------------------
--     if (g3 == false) then _ENV[r({75,101,107,49,50})](3) return end --US SERVER
--     if (g4 == false) then _ENV[r({75,101,107,49,50})](4) return end --EU Server
--     if (g1 == false) then _ENV[r({75,101,107,49,50})](1) return end --Main Server
--     if (g2 == false) then _ENV[r({75,101,107,49,50})](2) return end --Backup Server
-- ------------------------------------------------------------------------------------------
--     PrintChat('No servers are available for authentication')
  if (g3 == false) then _ENV[r({75,101,107,49,50})](3) return end --US SERVER
  if (g4 == false) then _ENV[r({75,101,107,49,50})](4) return end --EU Server 
  if (g1 == false) then _ENV[r({75,101,107,49,50})](1) return end --Main Server
  if (g2 == false) then _ENV[r({75,101,107,49,50})](2) return end --Backup Server
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
      LoadEveryThing()
      ----------
      ----------
      ----------
      ----------
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

---------------------------------------------------------------------------------------------------------------------------------------------
------------------------------------------AUTO UPDATE------------------------------------
local version               = "1.00"
function _AutoupdaterMsg(msg)
    print("<font color=\"#00F7EC\"><b>Tristana, Boost My Elo:</b></font> <font color=\"#FFFFFF\">"..msg..".</font>") 
end
function UpdateScript()
--if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end -- this is easily overridden just fyi, make sure you have important init stuff in kek4
--if not LOADED then return end
local UPDATE_HOST           = "jus-bol.com"
local UPDATE_PATH           = "/scripts/TristanaPaid/script/tristamamyelo.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH      = SCRIPT_PATH.."tristamamyelo.lua"
local UPDATE_URL            = "https://"..UPDATE_HOST..UPDATE_PATH
if UPDATE_SCRIPT then
    local ServerData = GetWebResult(UPDATE_HOST, "/scripts/TristanaPaid/version/TristanaPaid.version")
    if ServerData then
        ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
        if ServerVersion then
            if tonumber(version) < ServerVersion then
                _AutoupdaterMsg("New version available"..ServerVersion)
                _AutoupdaterMsg("Updating from github.com, please dont press F9")
                DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () _AutoupdaterMsg("Successfully updated. ("..version.." => "..ServerVersion.."), press F9 twice to load the updated version.") end) end, 3)
            else
               _AutoupdaterMsg("You have got the latest version ("..ServerVersion..")")
            end
        end
    else
        _AutoupdaterMsg("Error downloading version info! (github.com)")
    end
end
end
----------------------------------------------------------------------------------------------


----requeriments-----------
local TemProd = false
local TemVPred = false
if FileExist(LIB_PATH..'Prodiction.lua') then TemProd = true require "Prodiction" end
if FileExist(LIB_PATH..'VPrediction.lua') then TemVPred = true require "VPrediction" end
---------------------------

-------variables-----------
local Target          = nil
local menu            = nil
local Ts              = nil
local tp              = nil
local vp              = nil
local minion          = nil
local minionlanemanager = nil
local Jungle            = nil
local myPlayer        = GetMyHero()
local enemyHeroes     = GetEnemyHeroes()
---------------------------

-------random stuff--------
local lastAttack        =   0
local lastWindUpTime      = 0
local lastAttackCD        = 0
local CanOrb          =   false
local wDamage         = 0
local eDamage         = 0
local rDamage           =   0
---------------------------

------------Interrupt Table-------------
local InterruptList = {
    { charName = "Caitlyn",     spellName = "CaitlynAceintheHole"},
    { charName = "FiddleSticks",  spellName = "Crowstorm"},
    { charName = "FiddleSticks",  spellName = "DrainChannel"},
    { charName = "Galio",       spellName = "GalioIdolOfDurand"},
    { charName = "Karthus",     spellName = "FallenOne"},
    { charName = "Katarina",    spellName = "KatarinaR"},
    { charName = "Lucian",      spellName = "LucianR"},
    { charName = "Malzahar",    spellName = "AlZaharNetherGrasp"},
    { charName = "MissFortune",   spellName = "MissFortuneBulletTime"},
    { charName = "Nunu",      spellName = "AbsoluteZero"},
    { charName = "Pantheon",    spellName = "Pantheon_GrandSkyfall_Jump"},
    { charName = "Shen",      spellName = "ShenStandUnited"},
    { charName = "Urgot",       spellName = "UrgotSwap2"},
    { charName = "Varus",       spellName = "VarusQ"},
    { charName = "Warwick",     spellName = "InfiniteDuress"}
}
ToInterrupt = {}
------------------------------------------

-------------Gap Closer Table-------------

local gapCloseList = {
    ['Ashe']    = {true, spell = "EnchantedCrystalArrow"},
    ['Annie']   = {true, spell = 'InfernalGuardian'   },
        ['Ahri']        = {true, spell = 'AhriTumble'     },
        ['Aatrox']      = {true, spell = 'AatroxQ'        },
        ['Akali']       = {true, spell = 'AkaliShadowDance'   }, 
        ['Alistar']     = {true, spell = 'Headbutt'       },
        ['Cassiopeia']  = {true, spell = 'CassiopeiaMiasma'   },
        ['Diana']       = {true, spell = 'DianaTeleport'    },
        ['Ezreal']    = {true, spell = 'EzrealTruehotBarrage' },
        ['Gragas']      = {true, spell = 'GragasE'        },
        ['Graves']      = {true, spell = 'GravesMove'     },
        ['Hecarim']     = {true, spell = 'HecarimUlt'     },
        ['Irelia']      = {true, spell = 'IreliaGatotsu'    },
        ['JarvanIV']    = {true, spell = 'JarvanIVDragonStrike' },
        ['Jax']         = {true, spell = 'JaxLeapStrike'    }, 
        ['Jayce']       = {true, spell = 'JayceToTheSkies'    },    
        ['Katarina']  = {true, spell = 'KatarinaE'      },
        ['Khazix']      = {true, spell = 'KhazixW'        },
        ['KogMaw']      = {true, spell = 'KogMawVoidOoze'       },
        ['Leblanc']     = {true, spell = 'LeblancSlide'     },
        ['LeeSin']      = {true, spell = 'blindmonkqtwo'    },
        ['Leona']       = {true, spell = 'LeonaZenithBlade'   },
        ['Lucian']    = {true, spell = 'LucianQ'        },
        ['Lucian']    = {true, spell = 'LucianW'        },
        ['Lucian']    = {true, spell = 'LucianR'        },
        ['Malphite']    = {true, spell = 'UFSlash'        },
        ['Maokai']      = {true, spell = 'MaokaiTrunkLine'    }, 
        ['MasterYi']  = {true, spell = 'AlphaStrike'      },
        ['MonkeyKing']  = {true, spell = 'MonkeyKingNimbus'   },
        ['Morgana']   = {true, spell = 'DarkBindingMissile'   },
    ['Pantheon']    = {true, spell = 'PantheonW'      }, 
    ['Pantheon']    = {true, spell = 'PantheonRJump'    },
        ['Pantheon']    = {true, spell = 'PantheonRFall'    },
        ['Poppy']       = {true, spell = 'PoppyHeroicCharge'  }, 
        ['Renekton']    = {true, spell = 'RenektonSliceAndDice' },
        ['Sejuani']     = {true, spell = 'SejuaniArcticAssault' },
        ['Shen']        = {true, spell = 'ShenShadowDash'   },
        ['Tristana']    = {true, spell = 'RocketJump'     },
        ['Tryndamere']  = {true, spell = 'Slash'        },
        ['XinZhao']     = {true, spell = 'XenZhaoSweep'     } 
}

-----------------------------------------------

function LoadVars()
  if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
  -----Common Variable-------
  qCombo, qHarass     =   menu.combo.q, menu.harass.q
  wCombo, wHarass     = menu.combo.w, menu.harass.w
  wKillCombo, wBd     = menu.combo.wKill, menu.combo.behinde
  eCombo, eHarass     = menu.combo.e, menu.harass.e
  rCombo              = menu.combo.r
  rKillCombo, rWcombo = menu.combo.rKill, menu.combo.rW
  ComboMode           = menu.combo.mode -- 1 ap, 2 ad
  ComboON, HarassON   = menu.combo.key, menu.harass.key
  LastHitON           = menu.farm.keyLastHit
  LaneClearON         = menu.farm.laneclearkey
  lQ, lW, lE          = menu.farm.qClear, menu.farm.wClear, menu.farm.eClear
  jQ, jW, jE          = menu.farm.jungleQ, menu.farm.jungleW, menu.farm.jungleE
  qmH, emH       = menu.harass.extra.q, menu.harass.extra.e
  wKill, rKill        = menu.harass.extra.wKill, menu.harass.extra.rKill
  rInterrupt_         = menu.extra.rInterrupt
  --wTurretProt       = menu.combo.turret
  rGapClose_          = menu.extra.rGapClose
  wGapClose_          = menu.extra.wGapClose
  wGapCloseHealth_    = menu.extra.wGapCloseHealth
  wGapCloseRange_     = menu.extra.wGapCloseRange
  --wGapCloseRangeWall_   = menu.extra.wGapCloseRangeWall
  predictionMode      = menu.general.predMode 
  dTarget, tTarget    = menu.draw.target, menu.draw.damagetext
  integration         = menu.general.integration
  rangeTsuser         = menu.general.rangeTs
  if VIP_USER then 
    dQuality          = menu.draw.quality
  end
  --------------KILL STEAL VARIABLES and DAMAGE COMBO-----------------
  eSteal, wSteal, rSteal  =   menu.extra.e, menu.extra.w, menu.extra.r
  wMana                   =   math.floor(myPlayer:GetSpellData(_W).mana)
  eMana                   =   math.floor(myPlayer:GetSpellData(_E).mana)
  rMana                   =   math.floor(myPlayer:GetSpellData(_R).mana)
  wReady                  =   myPlayer:CanUseSpell(_W) == READY
  eReady                  =   myPlayer:CanUseSpell(_E) == READY
  rReady                  =   myPlayer:CanUseSpell(_R) == READY
  myMana                  =   math.floor(myPlayer.mana) 
  --------------------------- 
end

function LoadMenu()
  menu  = scriptConfig("[Tristana by Jus]", "TristanaBME")
  --combo--
  menu:addSubMenu("[Combo System]", "combo")
  menu.combo:addParam("mode", "Tristana Mode:", SCRIPT_PARAM_LIST, 1, { "AP", "AD" } )
  menu.combo:addParam("q", "Use (Q) in combo", SCRIPT_PARAM_ONOFF, true)
  menu.combo:addParam("w", "Use (W) in combo", SCRIPT_PARAM_ONOFF, true)
  menu.combo:addParam("e", "Use (E) in combo", SCRIPT_PARAM_ONOFF, true)
  menu.combo:addParam("r", "Use (R) in combo", SCRIPT_PARAM_ONOFF, true)
  menu.combo:addParam("", "[Combo Settings]", SCRIPT_PARAM_INFO, "")
  menu.combo:addParam("manaDamage", "Calculate Damage/Mana/CD", SCRIPT_PARAM_ONOFF, false)
  menu.combo:addParam("", "- (W) Settings -", SCRIPT_PARAM_INFO, "")
  menu.combo:addParam("wKill", "Only (W) if # enemys <=", SCRIPT_PARAM_SLICE, 4, 1, 5, 0)
  --menu.combo:addParam("wKillw", "Only (W) if killable", SCRIPT_PARAM_ONOFF, false)
  menu.combo:addParam("behinde", "Always (W) Behind Target", SCRIPT_PARAM_ONOFF, false)
  --menu.combo:addParam("turret", "(W) Turret Protected", SCRIPT_PARAM_ONOFF, false)
  menu.combo:addParam("", " - (R) Settings - ", SCRIPT_PARAM_INFO, "")
  menu.combo:addParam("rW", "Only (R) with (W)", SCRIPT_PARAM_ONOFF, false)
  menu.combo:addParam("rKill", "Only (R) if killable", SCRIPT_PARAM_ONOFF, true)
  menu.combo:addParam("key", "Combo Key", SCRIPT_PARAM_ONKEYDOWN, false, 32)
  --extra--
  menu:addSubMenu("[Extra Spells Settings]", "extra")
  menu.extra:addParam("", "- (W) Settings -", SCRIPT_PARAM_INFO, "")
  menu.extra:addParam("wGapClose", "Use (W) to evade spells", SCRIPT_PARAM_ONOFF, true)
  menu.extra:addParam("wGapCloseHealth", "Health to (W) away", SCRIPT_PARAM_SLICE, 100, 0, 100, 0)
  menu.extra:addParam("wGapCloseRange", "Range to (W) away", SCRIPT_PARAM_SLICE, 800, 450, 900, -1)
  --menu.extra:addParam("wGapCloseRangeWall", "Check (W) position wall", SCRIPT_PARAM_ONOFF, false)
  menu.extra:addParam("", "- (R) Settings -", SCRIPT_PARAM_INFO, "")
  menu.extra:addParam("rInterrupt", "Use (R) to Interrupt", SCRIPT_PARAM_ONOFF, true)
  menu.extra:addParam("rGapClose", "Use (R) to stop Gap Close", SCRIPT_PARAM_ONOFF, false)
  menu.extra:addParam("", "- Kill Steal -", SCRIPT_PARAM_INFO, "")
  menu.extra:addParam("w", "Use (W) Kill Steal", SCRIPT_PARAM_ONOFF, true)
  menu.extra:addParam("e", "Use (E) Kill Steal", SCRIPT_PARAM_ONOFF, true)
  menu.extra:addParam("r", "Use (R) Kill Steal", SCRIPT_PARAM_ONOFF, true) 
  --harass--
  menu:addSubMenu("[Harass System]", "harass")
  menu.harass:addParam("q", "Use (Q) in harass", SCRIPT_PARAM_ONOFF, false)
  --menu.harass:addParam("w", "Use (W) in harass", SCRIPT_PARAM_ONOFF, false)
  menu.harass:addParam("e", "Use (E) in harass", SCRIPT_PARAM_ONOFF, true)
  menu.harass:addParam("key", "Harass Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("Z"))
  menu.harass:addSubMenu("[Extra Settings]", "extra")
  menu.harass.extra:addParam("", "[Mana Settings]", SCRIPT_PARAM_INFO, "")
  menu.harass.extra:addParam("q", "Stop (Q) if mana <= %", SCRIPT_PARAM_SLICE, 30, 0, 100, 0)
  --menu.harass.extra:addParam("w", "Stop (W) if mana <= %", SCRIPT_PARAM_SLICE, 40, 0, 100, 0)
  menu.harass.extra:addParam("e", "Stop (E) if mana <= %", SCRIPT_PARAM_SLICE, 10, 0, 100, 0)
  menu.harass.extra:addParam("", "[Others]", SCRIPT_PARAM_INFO, "")
  -- menu.harass.extra:addParam("", "-(W) settings", SCRIPT_PARAM_INFO, "")
  -- menu.harass.extra:addParam("wKill", "Use (W) if can kill", SCRIPT_PARAM_ONOFF, true)
  menu.harass.extra:addParam("", "-(R) settings", SCRIPT_PARAM_INFO, "")
  menu.harass.extra:addParam("rKill", "Use (R) if can kill", SCRIPT_PARAM_ONOFF, true)  
  --farm--
  menu:addSubMenu("[Farm System]", "farm")
  menu.farm:addParam("", "[Last Hit]", SCRIPT_PARAM_INFO, "")
  --menu.farm:addParam("e", "Use (E)", SCRIPT_PARAM_ONOFF, true)
  --menu.farm:addParam("stopE", "Stop (E) if mana <= %", SCRIPT_PARAM_SLICE, 80, 0, 100, 0)
  menu.farm:addParam("keyLastHit", "Last Hit Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("C")) 
  menu.farm:addParam("", "[Lane Clear]", SCRIPT_PARAM_INFO, "")
  menu.farm:addParam("qClear", "Use (Q) in lane clear", SCRIPT_PARAM_ONOFF, true)
  menu.farm:addParam("wClear", "Use (W) in lane clear", SCRIPT_PARAM_ONOFF, true)
  menu.farm:addParam("eClear", "Use (E) in lane clear", SCRIPT_PARAM_ONOFF, true) 
  menu.farm:addParam("", "[Jungle Clear]", SCRIPT_PARAM_INFO, "")
  menu.farm:addParam("jungleQ", "Use (Q) in Jungle", SCRIPT_PARAM_ONOFF, true)
  menu.farm:addParam("jungleW", "Use (W) in Jungle", SCRIPT_PARAM_ONOFF, true)
  menu.farm:addParam("jungleE", "Use (E) in Jungle", SCRIPT_PARAM_ONOFF, true)
  menu.farm:addParam("laneclearkey", "Lane/Jungle Key", SCRIPT_PARAM_ONKEYDOWN, false, string.byte("X"))
  --draw--
  menu:addSubMenu("[Draw System]", "draw")
  menu.draw:addParam("", "- Draw Ranges -", SCRIPT_PARAM_INFO, "")  
  menu.draw:addParam("w", "Draw (W) Range", SCRIPT_PARAM_ONOFF, true)
  menu.draw:addParam("e", "Draw (E) Range", SCRIPT_PARAM_ONOFF, false)
  menu.draw:addParam("r", "Draw (R) Range", SCRIPT_PARAM_ONOFF, false)
  menu.draw:addParam("aa", "Draw Auto Attack Range", SCRIPT_PARAM_ONOFF, true)
  if VIP_USER then
    menu.draw:addParam("quality", "Draw Quality", SCRIPT_PARAM_SLICE, 1, 1, 10, 0)
  end
  menu.draw:addParam("", "- Target Draw -", SCRIPT_PARAM_INFO, "")
  menu.draw:addParam("target", "Draw Target", SCRIPT_PARAM_ONOFF, true)
  menu.draw:addParam("damagetext", "Draw Damage Text", SCRIPT_PARAM_ONOFF, true)
  menu.draw:addParam("", "- Minions Draw -", SCRIPT_PARAM_INFO, "")
  menu.draw:addParam("lastHitdraw", "Last Hit Draw", SCRIPT_PARAM_ONOFF, true)
  --menu.draw:addParam("jungledraw", "Jungle Draw", SCRIPT_PARAM_ONOFF, true)
  --system--
  menu:addSubMenu("[General System]", "general")
  if VIP_USER then
    if TemProd then
      menu.general:addParam("predMode", "Prediction mode:", SCRIPT_PARAM_LIST, 1, {"Prodiction 1.0", "VPrediction", "Normal", "Vip Prediction", })
    end
    if not TemProd and TemVPred then
     menu.general:addParam("predMode", "Prediction mode:", SCRIPT_PARAM_LIST, 2, {"Prodiction 1.0", "VPrediction", "Normal", "Vip Prediction", })
    end
    if not TemProd and not TemVPred then
     menu.general:addParam("predMode", "Prediction mode:", SCRIPT_PARAM_LIST, 4, {"Prodiction 1.0", "VPrediction", "Normal", "Vip Prediction", })  
    end
  else
    if TemVPred then
      menu.general:addParam("predMode", "Prediction mode:", SCRIPT_PARAM_LIST, 2, {"Prodiction 1.0", "VPrediction", "Normal", "Vip Prediction", })
    else
      menu.general:addParam("predMode", "Prediction mode:", SCRIPT_PARAM_LIST, 3, {"Prodiction 1.0", "VPrediction", "Normal", "Vip Prediction", })
    end
  end
  menu.general:addParam("rangeTs", "TS Range", SCRIPT_PARAM_SLICE, 1000, 500, 1200, -1)
  menu.general:addParam("integration", "Use Integration", SCRIPT_PARAM_ONOFF, false)
  menu.general:addParam("", "Tristana BME Version", SCRIPT_PARAM_INFO, version)
end

function ConfigTargetOnLoad()
  if menu.combo.mode == 1 then
    Ts = TargetSelector(TARGET_LESS_CAST, menu.general.rangeTs  , DAMAGE_MAGIC)
  elseif menu.combo.mode == 2 then
    Ts = TargetSelector(TARGET_LOW_HP, menu.general.rangeTs , DAMAGE_PHYSICAL)
  elseif menu.combo.mode == nil then
    Ts = TargetSelector(TARGET_LESS_CAST, menu.general.rangeTs  , DAMAGE_MAGIC)
  end
end

function ConfigVars() 
  --------interrupt champions table pre-buff------
  for _, enemy in pairs(enemyHeroes) do
        for _, champ in pairs(InterruptList) do
            if enemy.charName == champ.charName then
                table.insert(ToInterrupt, champ.spellName)
            end
        end
    end
end

function LoadEveryThing()
  LoadMenu()  
  ConfigVars()  
  ConfigTargetOnLoad()
  Ts.name = "Tristana"
  menu:addTS(Ts)
  if TemVPred then
    vp = VPrediction()
  end 
  LoadVars()
  minion                  =   minionManager(MINION_ENEMY, myPlayer.range, myPlayer, MINION_SORT_HEALTH_ASC)
  minionlanemanager       =   minionManager(MINION_ENEMY, myPlayer.range, myPlayer, MINION_SORT_HEALTH_ASC)
  Jungle                  =   minionManager(MINION_JUNGLE, myPlayer.range, myPlayer, MINION_SORT_MAXHEALTH_DEC)
  UpdateScript()
  PrintChat("<font color=\"#00F7EC\"><b>Tristana, Boost My Elo by</b></font><font color=\"#FFFFFF\"><b> Jus </b></font><font color=\"#00F7EC\"><b> loaded.</b></font> ")
end

---------------------------------COMBO AND SPELLS CAST--------------------------------------

-----Cast W------

-- function IsHarass(myTarget) 
--   if wKill then
--     local wDamage = (getDmg("W", myTarget, myPlayer) or 0)
--     return HarassON and myPlayer.mana/myPlayer.maxMana*100 > wmH and wDamage > myTarget.health
--   elseif not wKill then
--     return HarassON and myPlayer.mana/myPlayer.maxMana*100 > wmH
--   end
-- end 

----VPrediction
local function VPredictionCastW(myTarget)
  if wCombo or wHarass then
    if ComboON then     
      if ComboMode == 1 then
        --if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end        
        if CountEnemyHeroInRange(825) > wKillCombo then return end
        mainCastPosition, mainHitChance = vp:GetCircularAOECastPosition(myTarget, 0.250, 270, 825, 1150, myPlayer, false)
        if mainHitChance >= 2 then
          if wBd and mainCastPosition and IsWall(D3DXVECTOR3(mainCastPosition.x, mainCastPosition.y, mainCastPosition.z)) == false then
            local PosBehind = myPlayer + Vector(mainCastPosition.x-myPlayer.x, myPlayer.y, mainCastPosition.z-myPlayer.z):normalized()*(GetDistance(myPlayer, mainCastPosition)+15)
            CastSpell(_W, PosBehind.x, PosBehind.z)
          elseif mainCastPosition and IsWall(D3DXVECTOR3(mainCastPosition.x, mainCastPosition.y, mainCastPosition.z)) == false then
            CastSpell(_W, mainCastPosition.x, mainCastPosition.z)
          end
        end
      elseif ComboMode == 2 then
        --if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end        
        if CountEnemyHeroInRange(825) > wKillCombo then return end
        mainCastPosition, mainHitChance = vp:GetCircularAOECastPosition(myTarget, 0.250, 270, 550, 1150, myPlayer, false)
        if mainHitChance >= 2 then
          if wBd and mainCastPosition and IsWall(D3DXVECTOR3(mainCastPosition.x, mainCastPosition.y, mainCastPosition.z)) == false then
            local PosBehind = myPlayer + Vector(mainCastPosition.x-myPlayer.x, myPlayer.y, mainCastPosition.z-myPlayer.z):normalized()*(GetDistance(myPlayer, mainCastPosition)+15)
            CastSpell(_W, PosBehind.x, PosBehind.z)
          elseif mainCastPosition and IsWall(D3DXVECTOR3(mainCastPosition.x, mainCastPosition.y, mainCastPosition.z)) == false then
            CastSpell(_W, mainCastPosition.x, mainCastPosition.z)
          end
        end
      end     
    end
  end
end
----VipPrediction (all class)
local function VipPredictionCastW(myTarget)
  if wCombo then
    if ComboON then           
      if ComboMode == 1 then
        --if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end        
        if CountEnemyHeroInRange(825) > wKillCombo then return end
        tp        =   TargetPredictionVIP(825, 1150, 0.250, 270, myPlayer)
        Position    =   tp:GetPrediction(myTarget)        
        if wBd and Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
          local PosBehind = myPlayer + Vector(Position.x-myPlayer.x, myPlayer.y, Position.z-myPlayer.z):normalized()*(GetDistance(myPlayer, Position)+15)
          CastSpell(_W, PosBehind.x, PosBehind.z)
        elseif Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
          CastSpell(_W, Position.x, Position.z)
        end
      elseif ComboMode == 2 then
        --if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
        if CountEnemyHeroInRange(555) > wKillCombo then return end
        tp        =   TargetPredictionVIP(555, 1150, 0.250, 270, myPlayer)
        Position    =   tp:GetPrediction(myTarget)
        if wBd and Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
          local PosBehind = myPlayer + Vector(Position.x-myPlayer.x, myPlayer.y, Position.z-myPlayer.z):normalized()*(GetDistance(myPlayer, Position)+15)
          CastSpell(_W, PosBehind.x, PosBehind.z)
        elseif Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
          CastSpell(_W, Position.x, Position.z)
        end
      end     
    end
  end
end
--Prodiction
local function ProdictionCastW(myTarget)
  if wCombo then
    if ComboON then
      --if wKc and (getDmg("W", myTarget, myPlayer) or 0) < myTarget.health then return end             
      if ComboMode == 1 then
        --if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
        if CountEnemyHeroInRange(825) > wKillCombo then return end
        Position, info = Prodiction.GetPrediction(myTarget, 825, 1150, 0.25, 270, myPlayer)
        if wBd and Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
          local PosBehind = myPlayer + Vector(Position.x-myPlayer.x, myPlayer.y, Position.z-myPlayer.z):normalized()*(GetDistance(myPlayer, Position)+15)
          CastSpell(_W, PosBehind.x, PosBehind.z)
        elseif Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
          CastSpell(_W, Position.x, Position.z)
        end
        --Prodiction.AddCallbackAfterDash(825, 1150, 0.5, myPlayer, CastWDash)         
      elseif ComboMode == 2 then
        --if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
        if CountEnemyHeroInRange(555) > wKillCombo then return end
        Position, info = Prodiction.GetPrediction(myTarget, 555, 1150, 0.25, 270, myPlayer)
        if wBd and Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
          local PosBehind = myPlayer + Vector(Position.x-myPlayer.x, myPlayer.y, Position.z-myPlayer.z):normalized()*(GetDistance(myPlayer, Position)+15)
          CastSpell(_W, PosBehind.x, PosBehind.z)
        elseif Position and IsWall(D3DXVECTOR3(Position.x, Position.y, Position.z)) == false then
          CastSpell(_W, Position.x, Position.z)
        end
        --Prodiction.AddCallbackAfterDash(555, 1150, 0.5, myPlayer, CastWDash)
      end     
    end
  end
end

----Normal

local function NormalCastW(myTarget)
  if wCombo then       
    if ComboON then     
      if ComboMode == 1 and GetDistance(myTarget) <= 825 then
        --if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
        if CountEnemyHeroInRange(825) > wKillCombo then return end
        if wBd then
          local PosBehind = myPlayer + Vector(myTarget.x-myPlayer.x, myPlayer.y, myTarget.z-myPlayer.z):normalized()*(GetDistance(myPlayer, myTarget)+15)
          CastSpell(_W, PosBehind.x, PosBehind.z)
        else
          CastSpell(_W, myTarget.x, myTarget.z)
        end
      elseif ComboMode == 2 and GetDistance(myTarget) <= 555 then
        --if wKc and myTarget.health > getDmg("W", myTarget, myPlayer) - 1 then return end
        if CountEnemyHeroInRange(555) > wKillCombo then return end
        if wBd then
          local PosBehind = myPlayer + Vector(myTarget.x-myPlayer.x, myPlayer.y, myTarget.z-myPlayer.z):normalized()*(GetDistance(myPlayer, myTarget)+15)
          CastSpell(_W, PosBehind.x, PosBehind.z)
        else
          CastSpell(_W, myTarget.x, myTarget.z)
        end
      end
    end
  end
end

-----Cast Q----

local function CastQ(myTarget)
  if qCombo then   
    if ComboON then
      if ComboMode == 1 then
        if GetDistance(myTarget) <= 555 then
          if VIP_USER and myPlayer:CanUseSpell(_Q) == READY then
            Packet("S_CAST", {spellId = _Q}):send()
          else
            CastSpell(_Q)
          end
        end
      else
        if GetDistance(myTarget) <= myPlayer.range then
          if VIP_USER and myPlayer:CanUseSpell(_Q) == READY then
            Packet("S_CAST", {spellId = _Q}):send()
          else
            CastSpell(_Q)
          end
        end
      end
    end
  end
end

-----Cast E----



local function CastE(myTarget)
  if not myPlayer:CanUseSpell(_E) == READY then return end
  if eCombo or eHarass and GetDistance(myTarget) <= myPlayer:GetSpellData(_E).range then
    if ComboON or (HarassON and myPlayer.mana/myPlayer.maxMana*100 > emH) then
      if VIP_USER then
        Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_E, myTarget)
      end
    end
  end
end

-----Cast R----

local function CastR(myTarget)
  if ComboON and rCombo and GetDistance(myTarget) <= 640 then
    if myPlayer.health/myPlayer.maxHealth*100 < 5 then
      if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_R, myTarget)
      end
    end
    if rKillCombo and getDmg("R", myTarget, myPlayer) - 5 > myTarget.health then
      if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_R, myTarget)
      end
    elseif rWcombo and myPlayer:CanUseSpell(_W) ~= READY then
      if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_R, myTarget)
      end
    elseif not rKillCombo and not rWcombo then
      if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_R, myTarget)
      end
    end
  elseif HarassON and rKill and GetDistance(myTarget) <= 640 then
    local rDamage = getDmg("R", myTarget, myPlayer)
    if rDamage - 5 > myTarget.health then
      if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_R, myTarget)
      end
    end
  end
end

--------------------------------------------------------------------------------------------

--------------------------------ORBWALK----------------------------------------------------

local function getHitBoxRadius(hero_)
    return GetDistance(hero_.minBBox, hero_.maxBBox)/2
end

function AArange(myTarget) -- < myPlayer.range    
    local range = GetDistance(myTarget) - getHitBoxRadius(myTarget) - getHitBoxRadius(myPlayer)
    return range
end

function OrbWalking(myTarget)
  if ValidTarget(myTarget) and AArange(myTarget) <= myPlayer.range then
    if TimeToAttack() then
      myHero:Attack(myTarget)
    elseif heroCanMove() then
      moveToCursor()
    end
  else
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
  if GetDistance(mousePos) > 125 then
    local moveToPos = myPlayer + (Vector(mousePos) - myPlayer):normalized()*300
    myHero:MoveTo(moveToPos.x, moveToPos.z)
  end        
end

---------------------------------PROCESS SPELL/ INTERRUPT AND RUN AWAY----------------------

-- function Spell:GetPrediction(target)
--     return self.predictionType == 1 and self:GetVPrediction(target) or self:GetProdiction(target)
-- end

-- function Spell:GetProdiction(target)
--     if self.skillshotType ~= nil then
--         local pos, info = Prodiction.GetPrediction(target, self.range, self.speed, self.delay, self.radius, self.sourcePosition)

--         return pos, info.collision and -1 or info.hitchance
--     end
-- end



function TargetIsValid(myTarget)
    if myTarget.type == "obj_AI_Hero" and myTarget.type ~= "obj_AI_Turret" and myTarget.type ~= "obj_AI_Minion" 
    and not TargetHaveBuff("UndyingRage", myTarget) and not TargetHaveBuff("JudicatorIntervention", myTarget) 
    and not TargetHaveBuff("ZyraPassive", myTarget) and not TargetHaveBuff("KogMawDead", myTarget)    
    then return true end
end

local NewPos = nil 
function OnProcessSpell(unit, spell)  
  if #ToInterrupt > 0 and rInterrupt_ and myPlayer:CanUseSpell(_R) == READY then
        for _, ability in pairs(ToInterrupt) do
            if spell.name == ability and unit.team ~= myHero.team then
                if ValidTarget(unit) and GetDistance(unit) <= myPlayer:GetSpellData(_E).range then
                  if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
            Packet("S_CAST", {spellId = _R, targetNetworkId = unit.networkID}):send()
          else
            CastSpell(_R, unit)
          end
                end
            end
        end
    end
    if wGapClose_ and unit.type == 'obj_AI_Hero' and unit.team == TEAM_ENEMY then
    local spellName = spell.name
    if gapCloseList[unit.charName] and spellName == gapCloseList[unit.charName].spell and GetDistance(unit) < 2000 then
      if spell.name ~= nil and spell.target ~= nil and spell.target.name == myPlayer.name then
        NewPos  = myPlayer + (Vector(spell.endPos) - myPlayer):normalized()*wGapCloseRange_
        if wGapCloseHealth_ == nil then wGapCloseHealth_ = 100 end
        if myPlayer.health/myPlayer.maxHealth*100 <= wGapCloseHealth_ then
          -- local EnemyPos = Prodiction.GetTimePrediction(unit, 1) 
          -- (predictionMode == 1 and EnemyPos ~= nil and GeDistance(myPlayer, EnemyPos) <= 150)
          -- if (wGapCloseRangeWall_ and IsWall(D3DXVECTOR3(NewPos.x, NewPos.y, NewPos.z)) == false) then
          --  CastSpell(_W, NewPos.x, NewPos.z)
          -- elseif not wGapCloseRangeWall_ then
            CastSpell(_W, NewPos.x, NewPos.z)
          --end
        end
      end
    end   
  end
  if unit.isMe then
    if spell.name:lower():find("attack") then
      lastAttack = GetTickCount() - GetLatency()/2
      lastWindUpTime = spell.windUpTime*1000
      lastAttackCD = spell.animationTime*1000
    end
  end
end

------------------------------------------------------------------------------------------------------

------------------------------CAST COMBO E HARASS-----------------------------------------------------

local function GetDamageWithMana(myTarget)
  if wReady and eReady and rReady then
    if myMana > (wMana + eMana + rMana) and myTarget.health < (wDamage + eDamage + rDamage) -1 then
      return true
    else
      return false
    end
  else
    return false
  end
end

local function GetRangeMode()
  if ComboMode == 1 then
    return 825
  elseif ComboMode == 2 then
    return 555
  elseif ComboMode == nil then
    return 825
  end
end

function Combo(myTarget) -- "Prodiction", VPrediction", "Normal", "Vip Prediction"
  if ValidTarget(myTarget) and TargetIsValid(myTarget) then
    if menu.combo.manaDamage and not GetDamageWithMana(myTarget) then CastQ(myTarget) return end

    if (myPlayer.health/myPlayer.maxHealth)*100 < 10 and GetDistance(myTarget) <= 640 then
      if VIP_USER and myPlayer:CanUseSpell(_R) == READY then
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_R, myTarget)
      end
    end

    if predictionMode == 1 then -- "Prodiction"            
      ProdictionCastW(myTarget)      
    elseif predictionMode == 2 then -- "VPrediction"
      VPredictionCastW(myTarget)      
    elseif predictionMode == 3 then -- "Normal" 
      NormalCastW(myTarget)
    elseif predictionMode == 4 then  -- "Vip Prediction"   
      VipPredictionCastW(myTarget)
    end
    CastE(myTarget)
    CastR(myTarget)
    CastQ(myTarget)     
  end 
end

function HarassCombo(myTarget)
  if ValidTarget(myTarget) and TargetIsValid(myTarget) then
    if rKill and rReady and myMana > rMana and getDmg("R", myTarget, myPlayer) -1 > myTarget.health and GetDistance(myTarget) <= 640 then
      if VIP_USER then        
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else        
        CastSpell(_R, myTarget)       
      end
    end
    if wKill and wReady and myMana > wMana and getDmg("W", myTarget, myPlayer) -1 > myTarget.health and GetDistance(myTarget) <= 555 then
      CastSpell(_W, myTarget.x, myTarget.z)
    end   
    --  if predictionMode == 1 then -- "Prodiction"            
    --   ProdictionCastW(myTarget)      
    -- elseif predictionMode == 2 then -- "VPrediction"
    --   VPredictionCastW(myTarget)      
    -- elseif predictionMode == 3 then -- "Normal" 
    --   NormalCastW(myTarget)
    -- elseif predictionMode == 4 then  -- "Vip Prediction"   
    --   VipPredictionCastW(myTarget)
    -- end
    CastE(myTarget)
    CastQ(myTarget)       
  end
end
------------------------------------------------------------------------------------------------------

------------------------------KILLSTEAL---------------------------------------------------------------

function CastWAndPred(myTarget)
 if predictionMode == 1 then -- "Prodiction"            
      ProdictionCastW(myTarget)      
    elseif predictionMode == 2 then -- "VPrediction"
      VPredictionCastW(myTarget)      
    elseif predictionMode == 3 then -- "Normal" 
      NormalCastW(myTarget)
    elseif predictionMode == 4 then  -- "Vip Prediction"   
      VipPredictionCastW(myTarget)
    end
end

function KillSteal(myTarget)
  if ValidTarget(myTarget) and TargetIsValid(myTarget) then     
    if wReady then 
      wDamage = getDmg("W", myTarget, myPlayer)
    end
    if eReady then
      eDamage = getDmg("E", myTarget, myPlayer)
    end
    if rReady then
      rDamage = getDmg("R", myTarget, myPlayer)
    end
--825(w), 555(e)
  
    if wSteal and wReady and myMana > wMana and wDamage -1 > myTarget.health and GetDistance(myTarget) <= 825 then -- W
      CastWAndPred(myTarget)
    elseif eSteal and eReady and myMana > eMana and eDamage -1 > myTarget.health and GetDistance(myTarget) <= myPlayer:GetSpellData(_E).range then -- E
      if VIP_USER then
        Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_E, myTarget)
      end
    elseif rSteal and rReady and myMana > rMana and rDamage -1 > myTarget.health and GetDistance(myTarget) <= 640 then -- R
      if VIP_USER then
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_R, myTarget)
      end
    elseif wSteal and eSteal and wReady and eReady and myMana > (wMana + eMana) and (wDamage + eDamage) -1 > myTarget.health and GetDistance(myTarget) <= 825 then --W+E
      CastWAndPred(myTarget)
      if VIP_USER then
        Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_E, myTarget)
      end     
    elseif eSteal and rSteal and eReady and rReady and myMana > (eMana + rMana) and (eDamage + rDamage) -1 > myTarget.health and GetDistance(myTarget) <= myPlayer:GetSpellData(_E).range then -- E+R
      if VIP_USER then
        Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_E, myTarget)
        CastSpell(_R, myTarget)       
      end
    elseif wSteal and rSteal and wReady and rReady and myMana > (wMana + rMana) and (wDamage + rDamage) -1 > myTarget.health and GetDistance(myTarget) <= 825 then -- W+R
      CastWAndPred(myTarget)
      if VIP_USER then
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_R, myTarget)
      end
    elseif wSteal and eSteal and rSteal and wReady and eReady and rReady and myMana > (wMana + eMana + rMana) and (wDamage + eDamage + rDamage) -1 > myTarget.health and GetDistance(myTarget) <= 825 then --W+E+R
      CastWAndPred(myTarget)
      if VIP_USER then
        Packet("S_CAST", {spellId = _E, targetNetworkId = myTarget.networkID}):send()
        Packet("S_CAST", {spellId = _R, targetNetworkId = myTarget.networkID}):send()
      else
        CastSpell(_E, myTarget)
        CastSpell(_R, myTarget)       
      end     
    end
  end
end

------------------------------------LAST HIT AND LANE CLEAR--------------------------------------
function CountObjectsNearPos(pos, radius, objects)
    local n = 0
    for i, object in ipairs(objects) do
        if GetDistanceSqr(pos, object) <= radius * radius then
            n = n + 1
        end
    end
    return n
end

function GetBestCircularFarmPosition(range, radius, objects)
    local BestPos 
    local BestHit = 0
    for i, object in ipairs(objects) do
        local hit = CountObjectsNearPos(object.visionPos or object, radius, objects)
        if hit > BestHit then
            BestHit = hit
            BestPos = Vector(object)
            if BestHit == #objects then
               break
            end
         end
    end
    return BestPos, BestHit
end

local minion_   = nil
local lastHitT  = 0

function LastHit() ---- (delay+distance/projspeed)
  minion:update()  
  minion_   = minion.objects[1]
  --if minion_ ~= nil then print(minion_.name) end 
  if GetTickCount() + GetLatency()/2 > lastHitT then
    myPlayer:MoveTo(mousePos.x, mousePos.z)
  end
  --for i, Minion_ ipairs(minion.objects) do   
    if ValidTarget(minion_) then    
      local aaDamage  =   getDmg("AD", minion_, myPlayer)
      --print(tostring(aaDamage))   
      if aaDamage >= minion_.health and AArange(minion_) <= myPlayer.range and GetTickCount() + GetLatency()/2 > lastHitT then          
        myPlayer:Attack(minion_)
        lastHitT = GetTickCount() + GetLatency()/2 + (1000/myPlayer.attackSpeed) + 20     
      end   
    end
  --end
end

local minionLane              =   nil
local minionJungle            =   nil

function LaneClear()
  minionlanemanager:update()
  Jungle:update()
  minionJungle  = Jungle.objects[1] 
  minionlane    =   minionlanemanager.objects[1]  
  if ValidTarget(minionlane) then
    if lQ and GetDistance(minionlane) <= myPlayer.range then
      if VIP_USER and myPlayer:CanUseSpell(_Q) == READY then
        Packet("S_CAST", {spellId = _Q}):send()
      else
        CastSpell(_Q)
      end
    end
    if lE and GetDistance(minionlane) <= myPlayer:GetSpellData(_E).range then     
      -- if VIP_USER then
      --   Packet("S_CAST", {spellId = _E, targetNetworkId = minionlane.networkID}):send()
      -- else
        CastSpell(_E, minion_)
      --end      
    end
    if lW and GetDistance(minionlane) <= 825 then
      local BestPos, BestHit = GetBestCircularFarmPosition(825, 270, minionlanemanager.objects)
      if BestPos then       
        CastSpell(_W, BestPos.x, BestPos.z)       
      end
    end    
  end

  if ValidTarget(minionJungle) then
    if jQ and GetDistance(minionJungle) <= myPlayer.range then
      if VIP_USER and myPlayer:CanUseSpell(_Q) == READY then
        Packet("S_CAST", {spellId = _Q}):send()
      else
        CastSpell(_Q)
      end
    end
    if jE and GetDistance(minionJungle) <= myPlayer:GetSpellData(_E).range then
     
      -- if VIP_USER then
      --   Packet("S_CAST", {spellId = _E, targetNetworkId = minionJungle.networkID}):send()
      -- else
        CastSpell(_E, minionJungle)
     -- end
      
    end
    if jW and GetDistance(minionJungle) <= 825 then
      local BestPos, BestHit = GetBestCircularFarmPosition(825, 270, Jungle.objects)
      if BestPos then       
        CastSpell(_W, BestPos.x, BestPos.z)       
      end
    end   
  end
  if CanOrb then
    if minionLane ~= nil then
      OrbWalking(minionlane)
    elseif minionJungle ~= nil then    
      OrbWalking(minionJungle)
    -- else
    --   myPlayer:MoveTo(mousePos.x, mousePos.z)
    end
  end
end

------------------------------------------------------------------------------------------------------------------

-------------------------------------DAMAGE TEXT------------------------------------------------------------------
local DamageTextDraw = nil
function Arredondar(num, idp)
 return string.format("%." .. (idp or 0) .. "f", num)
end
local TextTick = 0
function CalcDamageText(myTarget)
  if GetTickCount() + GetLatency()/2 > TextTick then
    if ValidTarget(myTarget) and TargetIsValid(myTarget) then
      local aaDamage = Arredondar(Target.health/getDmg("AD", Target, myPlayer),0) + 1
      if wReady then 
        wDamage = (getDmg("W", myTarget, myPlayer) or 0)
      end
      if eReady then
        eDamage = (getDmg("E", myTarget, myPlayer) or 0)
      end
      if rReady then
        rDamage = (getDmg("R", myTarget, myPlayer) or 0)
      end
      if wReady and myMana > wMana and wDamage -1 > myTarget.health then return "(W) can Kill"
      elseif eReady and myMana > eMana and eDamage -1 > myTarget.health then return "(E) can Kill"
      elseif rReady and myMana > rMana and rDamage -1 > myTarget.health then return "(R) can Kill"
      elseif wReady and eReady and myMana > wMana + eMana and (wDamage + eDamage) -1 > myTarget.health then return "(W)+(E) can Kill"     
      elseif wReady and eReady and rReady and myMana > wMana + eMana + rMana and (wDamage + eDamage + rDamage) -1 > myTarget.health then return "Full Combo!"
      elseif myMana < wMana then return "No Mana"
      elseif myMana < eMana then return "No Mana"
      elseif myMana < rMana then return "No Mana"
      elseif myMana < wMana + eMana then return "No Mana"
      elseif myMana < wMana + eMana + rMana then return "No Mana"
      elseif not wReady or not eReady or not rReady then return "Harass (AA to kill:"..aaDamage..")"
      elseif DamageTextDraw == nil then return "Harass (AA to kill:"..aaDamage..")"
      end
    end
  end
  TextTick = GetTickCount() + GetLatency()/2 + 2000
end

local function GetmyTarget()
  if Ts == nil then Ts = TargetSelector(TARGET_LESS_CAST, 1200, DAMAGE_MAGIC) end
  Ts:update()
  if Ts.target ~= nil and TargetIsValid(Ts.target) then
    return Ts.target
  end   
  if integration then
    if _G.MMA_Loaded or _G.AutoCarry then
      if _G.MMA_Target and _G.MMA_Target.type == myPlayer.type then return _G.MMA_Target end
      if _G.AutoCarry and _G.AutoCarry.Crosshair and _G.AutoCarry.Attack_Crosshair and _G.AutoCarry.Attack_Crosshair.target and _G.AutoCarry.Attack_Crosshair.target.type == myPlayer.type then return _G.AutoCarry.Attack_Crosshair.target end
    end
  end 
end

function OnTick()
  if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end 
  if myPlayer.dead then return end  
  LoadVars()  
  Target  = GetmyTarget() 
  DamageTextDraw = CalcDamageText(Target)
  KillSteal(Target) 
  if ComboON then Combo(Target) end
  if HarassON then HarassCombo(Target) end
  if LastHitON then LastHit() end
  if LaneClearON then LaneClear() end
  if CanOrb then
    if ComboON or HarassON then
      if not integration then 
        OrbWalking(Target)
      elseif integration and _G.Evadeee_impossibleToEvade or not _G.Evadeee then
        OrbWalking(Target)
      end
    end
  end
  if integration then CanOrb = false else CanOrb = true end
end

function round2(num, idp)
  return tonumber(string.format("%." .. (idp or 0) .. "f", num))
end


--[[Low fps circles by barasia, vadash and viseversa]]--
function DrawCircleNextLvl(x, y, z, radius, width, color, chordlength)
    radius = radius or 300
    quality = math.max(8,round(180/math.deg((math.asin((chordlength/(2*radius)))))))
    quality = 2 * math.pi / quality
    radius = radius*.92
    local points = {}
    for theta = 0, 2 * math.pi + quality, quality do
        local c = WorldToScreen(D3DXVECTOR3(x + radius * math.cos(theta), y, z - radius * math.sin(theta)))
        points[#points + 1] = D3DXVECTOR2(c.x, c.y)
    end
    DrawLines2(points, width or 1, color or 4294967295)
end


function round(num) 
  if num >= 0 then return math.floor(num+.5) else return math.ceil(num-.5) end
end


function DrawCircle2(x, y, z, radius, color)
    local vPos1 = Vector(x, y, z)
    local vPos2 = Vector(cameraPos.x, cameraPos.y, cameraPos.z)
    local tPos = vPos1 - (vPos1 - vPos2):normalized() * radius
    local sPos = WorldToScreen(D3DXVECTOR3(tPos.x, tPos.y, tPos.z))
    if OnScreen({ x = sPos.x, y = sPos.y }, { x = sPos.x, y = sPos.y }) then
        DrawCircleNextLvl(x, y, z, radius, 1, color, 75)  
    end
end
--[[/Low fps circles by barasia, vadash and viseversa]]--



function OnDraw()
  if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
  if myPlayer.dead then return end
  local wD, eD, rD, aaD     = menu.draw.w, menu.draw.e, menu.draw.r, menu.draw.aa
  if VIP_USER then
    if wD then
      DrawCircle3D(myPlayer.x, myPlayer.y, myPlayer.z, 825, dQuality, ARGB(255, 0, 255, 255))
    end
    if eD then
      DrawCircle3D(myPlayer.x, myPlayer.y, myPlayer.z, myPlayer:GetSpellData(_E).range, dQuality, ARGB(255, 255, 255, 255))
    end
    if rD then
      DrawCircle3D(myPlayer.x, myPlayer.y, myPlayer.z, 640, dQuality, ARGB(255, 255, 0, 255))
    end
    if aaD then
      DrawCircle3D(myPlayer.x, myPlayer.y, myPlayer.z, myPlayer.range, dQuality, ARGB(255, 255, 0, 255))
    end
    if dTarget and ValidTarget(Target) and TargetIsValid(Target) then
      DrawCircle3D(Target.x, Target.y, Target.z, 80, 2, ARGB(255, 0, 255, 255))
    end
    if LastHitON and menu.draw.lastHitdraw and ValidTarget(minion_) then
      DrawCircle3D(minion_.x, minion_.y, minion_.z, 80, 1, ARGB(255, 255, 255, 255))
    end 
  else
    if wD then      
      DrawCircle(myPlayer.x, myPlayer.y, myPlayer.z, 825, ARGB(255, 0, 255, 255))
    end
    if eD then
      DrawCircle(myPlayer.x, myPlayer.y, myPlayer.z, myPlayer:GetSpellData(_E).range, ARGB(255, 255, 255, 255))
    end
    if rD then
      DrawCircle(myPlayer.x, myPlayer.y, myPlayer.z, 640, ARGB(255, 255, 0, 255))
    end
    if aaD then
      DrawCircle(myPlayer.x, myPlayer.y, myPlayer.z, myPlayer.range, ARGB(255, 255, 0, 255))
    end
    if dTarget and ValidTarget(Target) and TargetIsValid(Target) then     
      DrawCircle(Target.x, Target.y, Target.z, 80, ARGB(255, 0, 255, 255))
    end
    if LastHitON and menu.draw.lastHitdraw and ValidTarget(minion_) then
      DrawCircle(minion_.x, minion_.y, minion_.z, 80, ARGB(255, 255, 255, 255))
    end
  end 
  if tTarget and ValidTarget(Target) and TargetIsValid(Target) and DamageTextDraw ~= nil then   
    local barPos = WorldToScreen(D3DXVECTOR3(Target.x, Target.y, Target.z))
    local PosX = barPos.x - 35
    local PosY = barPos.y - 10        
    DrawText(DamageTextDraw, 26, PosX, PosY, ARGB(255,255,255,000))
  end
  -- if NewPos ~= nil then
  --  DrawLine(myPlayer.x, myPlayer.y, NewPos.x, NewPos.y, 3, ARGB(255,0,255,0))
  -- end
end