<?php exit() ?>--by apple 84.107.12.248
function OnLoad()
	config = scriptConfig("NFD Spells", "nfd.cfg") 
	config:addParam("info", "Ignore where you're looking at while casting spells", SCRIPT_PARAM_INFO, "")
	config:addParam("nfd0", "Manually cast Q without Face-Direction", SCRIPT_PARAM_ONOFF, false)
	config:addParam("nfd1", "Manually cast W without Face-Direction", SCRIPT_PARAM_ONOFF, false)
	config:addParam("nfd2", "Manually cast E without Face-Direction", SCRIPT_PARAM_ONOFF, false)
	config:addParam("nfd3", "Manually cast R without Face-Direction", SCRIPT_PARAM_ONOFF, false)
end

function CastSpellPacket(spell, targetNWID)
	packet = CLoLPacket(0x9A)
		packet:EncodeF(myHero.networkID)
		packet:Encode1(spell)
		packet:EncodeF(myHero.x)
		packet:EncodeF(myHero.z)
		packet:EncodeF(0)
		packet:EncodeF(0)
		packet:EncodeF(targetNWID)
		packet.dwArg1 = 1
		packet.dwArg2 = 0
	SendPacket(packet)
end

function OnSendPacket(p)
	if p.header == 0x9A then
		p.pos = 5
		local spell = p:Decode1()
		if config["nfd"..spell] then
			p.pos = 22
			local SpellNWID = p:DecodeF()
			if SpellNWID ~= nil and SpellNWID ~= 0 then
				p:Block()
				CastSpellPacket(spell, SpellNWID)
			end
		end
	end
end