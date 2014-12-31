<?php exit() ?>--by Extrinsic 76.247.28.71

local script_name = "vixen vi"

function dij(strth)
    local h = 5333
    for i = 1, #strth do
        h = ((h * (2 ^ 4)) + h) + string.byte(strth:sub(i,i))
        if h > 939393 then 
            h = h % 939393
        end
    end
    return h
end

function r(bytes)
  s = {}
  for i in pairs(bytes) 
    do s[i] = string.char(bytes[i]) 
  end
  return table.concat(s)
end

function ko(strz)
  if (strz) then
    strz = string.gsub (strz, "\n", "\r\n")
    strz = string.gsub (strz, "([^%w %-%_%.%~])",
    function (c) return string.format ("%%%02X", string.byte(c)) end)
    strz = string.gsub (strz, " ", "+")
  end
  return strz
end

local uh = tostring(dij(string.lower(GetUser())))
local hwid = ko(tostring(os.getenv(r({80,82,79,67,69,83,83,79,82,95,73,68,69,78,84,73,70,73,69,82}))..os.getenv(r({85,83,69,82,78,65,77,69}))..os.getenv(r({67,79,77,80,85,84,69,82,78,65,77,69}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,76,69,86,69,76}))..os.getenv(r({80,82,79,67,69,83,83,79,82,95,82,69,86,73,83,73,79,78})))) --credit Sida
local tosendhwid = tostring(dij(string.lower(hwid)))
local sc = tostring(dij(string.lower(script_name)))

function vij(str)
    cond = tostring(dij(uh .. ":" .. tosendhwid .. "::" .. sc .. "98")) == str
    if cond then
        _G.ExAuthed = true
    else
        PrintChat(uh .. ":" .. tosendhwid .. "::" .. sc .. "98")
        PrintChat(tostring(dij(uh .. ":" .. tosendhwid .. "::" .. sc .. "98")))
        PrintChat("Actual: " .. str)
        PrintChat("No authorization.  Please tell the developer to add this ID: " .. uh)
    end
    if not _G.ExAuthed then return end
    while not _G.ExAuthed do return end    
    if _ENV.e__OnLoad[2] then _ENV.e__OnLoad[1](_ENV.e__OnLoad[2]) end
    if _ENV.e__OnTick[1] then AddTickCallback(_ENV.e__OnTick[1]) end
    if _ENV.e__OnDraw[1] then AddDrawCallback(_ENV.e__OnDraw[1]) end
    if _ENV.e__OnProcessSpell[1] then AddProcessSpellCallback(_ENV.e__OnProcessSpell[1]) end
end

function Mjet()
    local req = "/auth1/hwidauth.please?z="
    req = req .. uh
    req = req .."&m="..tosendhwid.."&y="
    req = req .. sc
    GetAsyncWebResult("162.221.180.7", req, vij)
end

AddLoadCallback(Mjet)

_ENV.e__OnLoad = {function(a) if not _G.ExAuthed then return end a() end, _ENV.OnLoad}
_ENV.OnLoad = nil

_ENV.e__OnTick = {_ENV.OnTick}
_ENV.OnTick = function() end

_ENV.e__OnProcessSpell = {_ENV.OnProcessSpell}
_ENV.OnProcessSpell = function(a,b) end

_ENV.e__OnDraw = {_ENV.OnDraw}
_ENV.OnDraw = function() end
