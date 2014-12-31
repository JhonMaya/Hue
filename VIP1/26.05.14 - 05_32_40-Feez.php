<?php exit() ?>--by Feez 24.14.208.12
if myHero.charName ~= 'Riven' then return end

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
_ENV[r({115,99,114,105,112,116,110,97,109,101})] = 'korean riven' -- script name
_ENV[r({115,99,114,105,112,116,118,101,114})] = 1.0 -- version
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

ENV[r({104,119,105,100})] = ko(tostring(os.getenv(r({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82}))..os.getenv(r({85,83,69,82,78,65,77,69}))..os.getenv(r({67,79,77,80,85,84,69,82,78,65,77,69}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78})))) --credit Sida
--_ENV['hwid'] = "Feez & Kortex for ddev"
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
    PrintChat(r({60,102,111,110,116,32,99,111,108,111,114,61,39,35,69,51,65,65,48,48,39,62,75,111,114,101,97,110,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,57,57,48,48,48,48,39,62,82,105,118,101,110,58,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,70,70,70,70,70,70,39,62,78,111,32,115,101,114,118,101,114,115,32,97,118,97,105,108,97,98,108,101,46,60,47,102,111,110,116,62}))
  end
end

function Kek4(authCheck)
  areturn = _ENV[r({82,67,52})](rc(rd(authCheck)),false)
  dePack = JSON:decode(areturn)
  if (dePack[r({115,116,97,116,117,115})]) then
    if (dePack[r({115,116,97,116,117,115})] == 1) or (dePack[r({115,116,97,116,117,115})] == 7) then
      PrintChat(r({60,102,111,110,116,32,99,111,108,111,114,61,39,35,69,51,65,65,48,48,39,62,75,111,114,101,97,110,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,57,57,48,48,48,48,39,62,82,105,118,101,110,58,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,70,70,70,70,70,70,39,62,65,117,116,104,101,110,116,105,99,97,116,101,100,46,60,47,102,111,110,116,62}))
      _ENV[r({107,101,107,118,97,108,56,51,52})] = true
      	LoadConfig()
    else
      reason = dePack[r({114,101,97,115,111,110})]
      PrintChat(r({60,102,111,110,116,32,99,111,108,111,114,61,39,35,69,51,65,65,48,48,39,62,75,111,114,101,97,110,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,57,57,48,48,48,48,39,62,82,105,118,101,110,58,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,70,70,70,70,70,70,39,62,65,99,99,101,115,115,32,68,101,110,105,101,100,32,45,32})..reason..r({46}))
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









local version = 1.0
local TESTVERSION = false
local AUTOUPDATE = true
local UPDATE_HOST = "raw.github.com"
local UPDATE_PATH = "/Feeez/BoL/master/KoreanRiven.lua".."?rand="..math.random(1,10000)
local UPDATE_FILE_PATH = SCRIPT_PATH.."KoreanRiven.lua"
local UPDATE_URL = "https://"..UPDATE_HOST..UPDATE_PATH

function PrintText(text)
	print("<font color='#E3AA00'>Korean </font><font color='#990000'>Riven: </font><font color='#FFFFFF'>"..text.."</font>")
end

DelayAction(function()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if AUTOUPDATE then
		local ServerData = GetWebResult(UPDATE_HOST, "/Feeez/BoL/master/Versions/KoreanRiven.version")
		if ServerData then
      ServerVersion = type(tonumber(ServerData)) == "number" and tonumber(ServerData) or nil
      if ServerVersion then
        if tonumber(version) < ServerVersion then
          PrintText("New version available: "..ServerVersion)
          PrintText("Updating Annie. Do not F9 until done.")
          DelayAction(function() DownloadFile(UPDATE_URL, UPDATE_FILE_PATH, function () PrintText("Updated version "..version.." to version "..ServerVersion..". Press F9 twice to finish update.") end) end, 3)
        end
      end
    else
      PrintText("Error downloading version info")
    end
	end
end, 10)

require 'VPrediction'

local qready, wready, eready, rready, ultAvailable
local qd, wd, rd
local qstate = 0
local passiveOn = false
local ultActive = false
local target
local disableMove = false
local aastacks = 0
local numHits = 0
local buffering = false
local hydraslot, hydraready, omenslot, omenready, tiaslot, tiaready = nil, false, nil, false, nil, false
local DrawPos
local cancelAA = false
local resetCount = 0
local myRange = 125
local SOWActive = false
local canMove


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
			if stringff or stringfg or stringfh or stringfi then PrintChat(r({60,102,111,110,116,32,99,111,108,111,114,61,39,35,69,51,65,65,48,48,39,62,75,111,114,101,97,110,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,57,57,48,48,48,48,39,62,82,105,118,101,110,58,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,70,70,70,70,70,70,39,62,72,111,115,116,115,32,70,105,108,101,32,77,111,100,105,102,101,100,58,32,65,99,99,101,115,115,32,68,101,110,105,101,100,46,60,47,102,111,110,116,62})) return end
		end
	end
	_ENV[r({112,97,99,107,73,116})] = _ENV[r({82,67,52})](_ENV[r({112,97,99,107,73,116})],true) --encrypt table

	_ENV[r({75,101,107,56})]() --check site 1
	_ENV[r({75,101,107,57})]() --check site 2
	_ENV[r({75,101,107,49,48})]() --check site 3
	PrintChat(r({60,102,111,110,116,32,99,111,108,111,114,61,39,35,69,51,65,65,48,48,39,62,75,111,114,101,97,110,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,57,57,48,48,48,48,39,62,82,105,118,101,110,58,32,60,47,102,111,110,116,62,60,102,111,110,116,32,99,111,108,111,114,61,39,35,70,70,70,70,70,70,39,62,86,97,108,105,100,97,116,105,110,103,32,65,99,99,101,115,115,46,60,47,102,111,110,116,62})) -- Validating Access
	_ENV[r({68,101,108,97,121,65,99,116,105,111,110})](_ENV[r({75,101,107,49,49})],4) -- run the auth after checking sites delayaction,4
end

function LoadConfig()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	Config = scriptConfig("Korean Riven", "riven")

	Config:addSubMenu("Orbwalker", "orbwalker")
	Config:addSubMenu("Draw", "draw")

	Config.draw:addParam("aarange", "AA Range", SCRIPT_PARAM_ONOFF, true)
  Config.draw:addParam("target", "Target", SCRIPT_PARAM_ONOFF, true)
  Config.draw:addParam("mouse", "Mouse range", SCRIPT_PARAM_ONOFF, false)

	Config.orbwalker:addParam("orbwalking", "Orbwalk", SCRIPT_PARAM_ONKEYDOWN, false, 32)

	Config:addParam("print", "Print type", SCRIPT_PARAM_ONOFF, true)

	Config:addParam("version", "Version:", SCRIPT_PARAM_INFO, tostring(version))


	VP = VPrediction()

	DelayAction(function() PrintText('Loaded.') end, 1)
end


function ObjectFromNetworkID(id)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	for i = 1, objManager.maxObjects do
		local object = objManager:GetObject(i)
		if object ~= nil and object.networkID == id then return object end
	end
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

	if Config.draw.aarange then 
		DrawCircle2(myHero.x, myHero.y, myHero.z, myRange, ARGB(255,227,170,0))
	end


	if Config.draw.mouse then DrawCircle2(mousePos.x, myHero.y, mousePos.z, 500, ARGB(255,255,0,0)) end

	if target ~= nil and Config.draw.target then DrawCircle2(target.x, target.y, target.z, 70, ARGB(255,211,44,44)) end

	--if DrawPos ~= nil then DrawCircle2(DrawPos.x, myHero.y, DrawPos.z, 92, ARGB(255,211,44,44)) end

end

function Attack(target)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if target ~= nil then Packet("S_MOVE", {type = 3, x = target.x, y = target.z, targetNetworkId = target.networkID}):send() end
end

function MoveTo(xx,zz)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if xx ~= nil and zz ~= nil then Packet("S_MOVE", {type = 2, x = xx, y = zz}):send() end
end

function MyRange(target)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	local myRange = myHero.range + VP:GetHitBox(myHero)
	if target and ValidTarget(target) then
		myRange = myRange + VP:GetHitBox(target)
	end
	return myRange - 4
end

function KGetTarget()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if GetTarget() and GetTarget().type == myHero.type then return GetTarget() end
	local champTable = {}

	for i=1, heroManager.iCount do
		local enemy = heroManager:getHero(i)
		if enemy.team ~= myHero.team then
			if ValidTarget(enemy, 500) and GetDistanceSqr(enemy.visionPos, mousePos) < 250000 then
				table.insert(champTable, enemy)
			end
		end
	end	

	local function getDmgPercent(champion)
		return (champion.health - myHero:CalcDamage(champion, myHero.damage)) / champion.maxHealth
	end

	local lastChampDmg
	local bestChamp

	if #champTable == 1 then 
		bestChamp = champTable[1]
	else
		for i=1, #champTable do
			if not lastChampDmg then 
				lastChampDmg = getDmgPercent(champTable[i])
				bestChamp = champTable[i]
			else
				if getDmgPercent(champTable[i]) < lastChampDmg then
					lastChampDmg = getDmgPercent(champTable[i])
					bestChamp = champTable[i]
				end
			end
		end
	end

	return bestChamp
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

function CalcRDamage(target)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if target ~= nil then
		local rdamage = math.floor((((myHero:GetSpellData(_R).level-1) * 40) + 80))
		local percent = (1 - (target.health / target.maxHealth)) / .01
		local multiplier = (.0267 * percent) + .6
		if multiplier > 2 then multiplier = 2 end
		local totalDamage = rdamage + (multiplier * rdamage)
		

		return myHero:CalcDamage(target, totalDamage)
	end
end

function canKill(combo, enemy)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	local totalDmg = 0
	if enemy ~= nil then
		if type(combo) == 'table' then
			for i=1, #combo do
				if combo[i] == 'Q' then totalDmg = totalDmg + myHero:CalcDamage(enemy, qd) end
				if combo[i] == 'W' then totalDmg = totalDmg + myHero:CalcDamage(enemy, wd) end
				if combo[i] == 'R' then totalDmg = totalDmg + CalcRDamage(enemy) end
			end
		end
		return totalDmg > enemy.health
	end
	return false
end

function Checks()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	target = KGetTarget()
	SOWActive = Config.orbwalker.orbwalking

	if target ~= nil then myRange = MyRange(target) end

	qready = (myHero:CanUseSpell(_Q) == READY)
	wready = (myHero:CanUseSpell(_W) == READY)
	eready = (myHero:CanUseSpell(_E) == READY)
	rready = (myHero:CanUseSpell(_R) == READY)
	ultAvailable = ultActive and rready
	hydraslot = GetInventorySlotItem(3074)
	hydraready = (hydraslot ~= nil and myHero:CanUseSpell(hydraslot) == READY)
	tiaslot = GetInventorySlotItem(3077)
	tiaready = (tiaslot ~= nil and myHero:CanUseSpell(tiaslot) == READY)
	omenslot = GetInventorySlotItem(3143)
	tiaready = (omenlort ~= nil and myHero:CanUseSpell(omenslot) == READY)
	qd = math.floor((((myHero:GetSpellData(_Q).level-1) * 20) + 10)+((((myHero:GetSpellData(_Q).level-1) * .05) + .4)*myHero.totalDamage))
	wd = math.floor((((myHero:GetSpellData(_W).level-1) * 30) + 50)+(myHero.totalDamage - myHero.damage))

	if aastacks > 0 then passiveOn = true else passiveOn = false end


	if TargetHaveBuff('riventricleavesoundtwo', myHero) and qready then
		qstate = 3
	elseif TargetHaveBuff('riventricleavesoundone', myHero) and qready then
		qstate = 2
	elseif qready then
		qstate = 1
	else 
		qstate = 0
	end 

	if myHero:GetSpellData(_R).name == "rivenizunablade" then ultActive = true else ultActive = false end

end

function CastR(target)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	--VPrediction:GetConeAOECastPosition(unit, delay, angle, range, speed, from)
	if target ~= nil then
		local castPos = VP:GetConeAOECastPosition(target, .25, 45, 900, 1200, myHero)
		PacketCast(_R, castPos.x, castPos.z)
	end
end

function IsEasyKill(enemy)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	local predDamage = myHero.totalDamage + (.2 * myHero.totalDamage)
	if (myHero:CalcDamage(enemy, predDamage) * 4 > enemy.health) and not canKill({'R', 'Q'}, target) then return true end
	return false
end

function NumberOfEnemiesInRange(range, delay)
	local numEnemies = 0
	for i=1, heroManager.iCount do
		local enemy = heroManager:getHero(i)
		if enemy.team ~= myHero.team then
			if ValidTarget(enemy, range+500) then
				local pos = VP:GetPredictedPos(enemy, delay)
				if GetDistanceSqr(pos, myHero.visionPos) < range^2 then numEnemies = numEnemies + 1 end
			end
		end
	end	
	return numEnemies
end

function OnTick()
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	Checks()

	if target ~= nil and SOWActive then
		local delay = .17 + GetLatency()/2000
		local pos = VP:GetPredictedPos(target, delay)

		if rready and not ultActive and IsEasyKill(target) then PacketCast(_R) end

		DrawPos = pos
		local distance = GetDistanceSqr(myHero.visionPos, pos)
		if distance > myRange^2 and not isFacing(target, myHero, 400) then
			if eready and not qready and distance < myRange^2 + 62500 then
				if wready then 
					PacketCast(_E, pos.x, pos.z); DelayAction(function() if (hydraready or tiaready or wready) then if hydraready then PacketCast(hydraslot) elseif tiaready then PacketCast(tiaslot) elseif wready then PacketCast(_W) end end end, .2)
				else
					PacketCast(_E, pos.x, pos.z)
				end
			elseif qready and distance < myRange^2 + 50625 and aastacks < 2 then
				PacketCast(_Q, pos.x, pos.z)
			elseif qstate == 1 and distance < myRange^2 + 101250 then
				PacketCast(_Q, pos.x, pos.z)
				DelayAction(function() PacketCast(_Q, pos.x, pos.z) end, .3)
			elseif qready and distance < myRange^2 + 50625 + 62500 then
				PacketCast(_E, pos.x, pos.z)
				DelayAction(function() PacketCast(_Q, pos.x, pos.z) end, .5)
			else
				MoveTo(pos.x, pos.z)
			end

		elseif distance <= myRange^2 and isFacing(target, myHero, 500) then
			Attack(target)
		end
	elseif not target and SOWActive then
		MoveTo(mousePos.x, mousePos.z)
	end

	if target ~= nil and wready and not qready and NumberOfEnemiesInRange(125, .27 + GetLatency()/2000) >= 2 then PacketCast(_W) end

	if SOWActive and target ~= nil then
		if target ~= nil and qstate == 0 and wready and not passiveOn then
			local pos = VP:GetPredictedPos(target, .25)
			if GetDistanceSqr(myHero.visionPos, pos) < 67600 then
				PacketCast(_W)
			end
		elseif target ~= nil and wready and not isFacing(target, myHero, 450) then
			local pos = VP:GetPredictedPos(target, .25)
			if GetDistanceSqr(myHero.visionPos, pos) < 67600 then
				PacketCast(_W)
			end
		end

		if not canKill({'R'}, target) and canKill({'R', 'Q'}, target) and qready and ultAvailable then
			local pos = VP:GetPredictedPos(target, .3)
			if GetDistanceSqr(myHero.visionPos, pos) < 67600 then
				CastR(target)
				DelayAction(function() PacketCast(_Q, pos.x, pos.z) end, .3)
			end
		elseif canKill({'R'}, target) and not qready and ultAvailable and GetDistanceSqr(myHero.visionPos, target.visionPos) > myRange^2 then
			CastR(target)
		end

	end


	if target ~= nil and target.type == myHero.type and hydraready and not qready and GetDistanceSqr(myHero.visionPos, target) < 67600 then PacketCast(hydraslot) end
end


function OnSendPacket(p)
if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if p.header == Packet.headers.S_CAST then
		local decodedPacket = Packet(p)
		local spellId = decodedPacket:get('spellId')
		local source = decodedPacket:get('sourceNetworkId')
		if source == myHero.networkID then
			if spellId == _Q or spellId == _W or spellId == _E or spellId == _R then
				DelayAction(function() Emote() end, 0)
			end
		end
	end
end

function PacketCast(spell, param1, param2)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if spell ~= nil then
		--Packet Cast
		if param1 ~= nil and param2 ~= nil then
			Packet("S_CAST", {spellId = spell, toX = param1, toY = param2, fromX = param1, fromY = param2}):send()
		elseif param1 ~= nil and param2 == nil then
			Packet("S_CAST", {spellId = spell, targetNetworkId = param}):send()
		elseif param1 == nil and param2 == nil then
			Packet("S_CAST", {spellId = spell}):send()
		end
		--Animation Cancel
		if spell == _Q or spell == _W or spell == _E or spell == _R then
			DelayAction(function() Emote() end, 0)
		end
		if spell == hydraslot or spell == tiaslot then
			if wready then DelayAction(function() PacketCast(_W) end, 0) end
		end
	end
end

--[[local p = CLoLPacket(0x64)
p:EncodeF(myHero.networkID)
p:Encode1(music) -- 2 dodge, 3 crit, 4 phys, 5 miss, 6 phys?, 7 phys?, 8 invunerable, 9, 10 dodge, 11 crit
p:EncodeF(myHero.networkID)
p:EncodeF(myHero.networkID)
p:EncodeF(-100)
--STP(c, ps)]]

--[[function OnTick()
 RecvPacket(p)
end]]

function OnRecvPacket(p)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	local pheader = string.format('0x%02X', p.header)
	if pheader == '0x64' and SOWActive then
		--if Packet(p):containsFloat(myHero.networkID) then print(Packet(p):containsFloat(myHero.networkID)) end
		p.pos = 1
		local target = p:DecodeF()
		local Type = p:Decode1()
		local target_ = p:DecodeF()
		local source = p:DecodeF()
		local damage = p:DecodeF()
		if source == myHero.networkID and ObjectFromNetworkID(target).type == myHero.type then 
			target = ObjectFromNetworkID(target)
			if Type == 12 then
				SendQ()
				if target ~= nil then MoveTo(target.x, target.z) end
				resetCount = resetCount + 1
				if Config.print then print(resetCount..": AA") end
			elseif Type == 4 then
				if hydraready then PacketCast(hydraslot);PacketCast(_W) end
				if tiaready then PacketCast(tiaslot) end
				resetCount = resetCount + 1
				if Config.print then print(resetCount..": Spell") end
			end
		end
	end
end


function SendQ()
if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if target ~= nil and GetDistanceSqr(myHero.visionPos, target.visionPos) < 160000 then
		local delay = .26 + GetLatency()/2000
		local pos = VP:GetPredictedPos(target, delay)
		PacketCast(_Q, pos.x, pos.z)
	end
end

function Emote(param)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	p = CLoLPacket(0x47)
	p:EncodeF(myHero.networkID)
	p:Encode1(2)
	p.dwArg1 = 1
	p.dwArg2 = 0
	SendPacket(p)
end

function OnProcessSpell(unit, spell)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	--if unit.isMe then print("Unit: "..unit.charName.. " , Spell: "..spell.name.." , Delay: "..spell.windUpTime.." , Animation Time: "..spell.animationTime.." , Distance: "..GetDistance(myHero.visionPos, spell.endPos)) end
	if unit.isMe then
		if string.lower(spell.name) == 'itemtiamatcleave' then
			--if wready then DelayAction(function() PacketCast(_W) end, .17) end
		end
	end
end

function OnGainBuff(unit, buff)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if unit.isMe and buff.name == 'rivenpassiveaaboost' then aastacks = buff.stack end
end

function OnUpdateBuff(unit, buff)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if unit.isMe and buff.name == 'rivenpassiveaaboost' then aastacks = buff.stack end
end

function OnLoseBuff(unit, buff)
	if not _ENV[r({107,101,107,118,97,108,56,51,52})] then return end
	if unit.isMe and buff.name == 'rivenpassiveaaboost' then aastacks = 0 end
end