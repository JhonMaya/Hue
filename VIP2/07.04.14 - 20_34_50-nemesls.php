<?php exit() ?>--by nemesls 80.57.15.158
function _G._InjectPS(cipher, env)
	local fn = { setmetatable, tonumber, tostring, IsDDev, debug.getinfo, GetWebResult, DownloadFile, GetUser, Base64Encode, Base64Decode, string.reverse, string.lower, string.gsub, string.sub, assert, ipairs, pairs, rawget,  select, debug.debug, debug.getlocal, debug.gethook, debug.setfenv, debug.sethook, debug.setlocal, debug.upvalueid, os.clock, string.find, string.format, table.insert, load }
	for i, v in ipairs(fn) do
		if debug.getinfo(v).what ~= ("C") then
			PrintChat('[PS] Not today: ' .. tostring(i))
			return
		end
		if debug.getinfo(v).source ~= "=[C]" then
			PrintChat('[PS] Not today: ' .. tostring(i))
			return
		end
		if debug.getinfo(v).linedefined ~= -1 then
			PrintChat('[PS] Not today: ' .. tostring(i))
			return
		end
		if debug.getinfo(v).namewhat ~= '' then
			PrintChat('[PS] Not today: ' .. tostring(i))
			return
		end
	end
	
	assert(load(Base64Decode(cipher:reverse()), nil, 'bt', env))()
end

_G.EMOTE_DANCE = 0
_G.EMOTE_TAUNT = 1
_G.EMOTE_LAUGH = 2
_G.EMOTE_JOKE = 3

_ENV = AdvancedCallback:register('OnEmote')

function OnRecvPacket(p)
	if p.header == 0x41 then 
		p.pos = 1
		local networkID = p:DecodeF()
        local typeID = p:Decode1()
        local unit = objManager:GetObjectByNetworkId(networkID)

        if unit and unit.valid then
            if AdvancedCallback:OnEmote(unit, typeID) == false then
                p:Block()
            end
        end
    end
end